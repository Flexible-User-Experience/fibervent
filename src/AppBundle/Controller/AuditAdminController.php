<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Audit;
use AppBundle\Entity\User;
use AppBundle\Form\Type\AuditEmailSendFormType;
use AppBundle\Service\AuditPdfBuilderService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class AuditAdminController
 *
 * @category Controller
 * @package  AppBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
class AuditAdminController extends AbstractBaseAdminController
{
    /**
     * Export Audit in PDF format action
     *
     * @param Request $request
     *
     * @return Response
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function pdfAction(Request $request = null)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Audit $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('Unable to find audit record with id: %s', $id));
        }

        /** @var AuditPdfBuilderService $apbs */
        $apbs = $this->get('app.audit_pdf_builder');
        $pdf = $apbs->build($object);

        return new Response($pdf->Output('informe_auditoria_' . $object->getId() . '.pdf', 'I'), 200, array('Content-type' => 'application/pdf'));
    }

    /**
     * Custom show action redirect to public frontend view
     *
     * @param null $id
     *
     * @return Response
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function showAction($id = null)
    {
        $request = $this->resolveRequest();
        $id = $request->get($this->admin->getIdParameter());

        /** @var Audit $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('Unable to find audit record with id: %s', $id));
        }

        $sortedBladeDamages = array();
        $bdr = $this->get('app.blade_damage_repository');
        foreach ($object->getAuditWindmillBlades() as $auditWindmillBlade) {
            array_push($sortedBladeDamages, $bdr->getItemsOfAuditWindmillBladeSortedByRadius($auditWindmillBlade));
        }

        return $this->render(
            ':Admin/Audit:show.html.twig',
            array(
                'action'             => 'show',
                'object'             => $object,
                'sortedBladeDamages' => $sortedBladeDamages,
            )
        );
    }

    /**
     * Custom email action
     *
     * @param Request $request
     *
     * @return Response
     */
    public function emailAction(Request $request = null)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Audit $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('Unable to find audit record with id: %s', $id));
        }

        /** @var AuditPdfBuilderService $apbs */
        $apbs = $this->get('app.audit_pdf_builder');
        $pdf = $apbs->build($object);
        $pdf->Output($this->getDestAuditFilePath($object), 'F');
        
        $form = $this->createForm(new AuditEmailSendFormType(), $object, array(
            'default_msg'    => 'Adjunto archivo resultado auditoria número ' . $object->getId(),
            'to_emails_list' => $this->getToEmailsList($object),
            'cc_emails_list' => $this->getCcEmailsList($object),
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $to = $form->get('to')->getData();
            $cc = $form->get('cc')->getData();
            $this->get('app.notification')->deliverAuditEmail($form, $this->getDestAuditFilePath($object));
            $this->addFlash('sonata_flash_success', 'La auditoria núm. ' . $object->getId() . ' s\'ha enviat correctament a ' . $to . ($cc ? ' amb còpia per a ' . $cc : ''));

            return new RedirectResponse($this->admin->generateUrl('list'));
        }

        return $this->render(
            ':Admin/Audit:email.html.twig',
            array(
                'action'         => 'show',
                'object'         => $object,
                'form'           => $form->createView(),
                'pdf_short_path' => $this->getShortAuditFilePath($object),
            )
        );
    }

    /**
     * @param Audit $audit
     *
     * @return string
     */
    private function getDestAuditFilePath(Audit $audit)
    {
        $krd = $this->getParameter('kernel.root_dir');
        $path = $krd . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'web' . $this->getShortAuditFilePath($audit);
        
        return $path;
    }

    /**
     * @param Audit $audit
     *
     * @return string
     */
    private function getShortAuditFilePath(Audit $audit)
    {
        return DIRECTORY_SEPARATOR . 'pdfs' . DIRECTORY_SEPARATOR . 'Auditoria-' . $audit->getId() . '.pdf';
    }

    /**
     * @param Audit $audit
     *
     * @return array
     */
    private function getToEmailsList(Audit $audit)
    {
        $availableMails = $this->commonEmailsList($audit);

        return $availableMails;
    }

    /**
     * @param Audit $audit
     *
     * @return array
     */
    private function getCcEmailsList(Audit $audit)
    {
        $availableMails = $this->commonEmailsList($audit);
        $users = $this->get('app.user_repository')->findOnlyAvailableSortedByName($audit->getCustomer());
        /** @var User $user */
        foreach ($users as $user) {
            $availableMails[$user->getEmail()] = $user->getFullname() . ' <' . $user->getEmail() . '>';
        }

        return $availableMails;
    }

    /**
     * @param Audit $audit
     *
     * @return array
     */
    private function commonEmailsList(Audit $audit)
    {
        $availableMails = array();
        if ($audit->getCustomer()->getEmail()) {
            $availableMails[$audit->getCustomer()->getEmail()] = $audit->getCustomer()->getName() . ' <' . $audit->getCustomer()->getEmail() . '>';
        }
        if ($audit->getWindfarm() && $audit->getWindfarm()->getManager()) {
            $availableMails[$audit->getWindfarm()->getManager()->getEmail()] = $audit->getWindfarm()->getMangerFullname() . ' <' . $audit->getWindfarm()->getManager()->getEmail() . '>';
        }
        if ($audit->getCustomer()) {
            /** @var User $user */
            foreach ($audit->getCustomer()->getContacts() as $user) {
                if ($user->isEnabled()) {
                    $availableMails[$user->getEmail()] = $user->getFullname() . ' <' . $user->getEmail() . '>';
                }
            }
        }

        return $availableMails;
    }
}
