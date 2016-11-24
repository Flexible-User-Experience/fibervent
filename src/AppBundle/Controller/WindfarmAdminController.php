<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Audit;
use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Entity\Windfarm;
use AppBundle\Enum\WindfarmLanguageEnum;
use AppBundle\Form\Type\WindfarmAnnualStatsFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class WindfarmAdminController
 *
 * @category Controller
 * @package  AppBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
class WindfarmAdminController extends AbstractBaseAdminController
{
    /**
     * Show windfarm audits list view
     *
     * @param Request $request
     *
     * @return Response
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function auditsAction(Request $request = null)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Windfarm $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('Unable to find windfarm record with id: %s', $id));
        }

        return $this->render(
            ':Admin/Windfarm:map.html.twig',
            array(
                'action' => 'show',
                'object' => $object,
            )
        );
    }

    /**
     * Create windmills map view
     *
     * @param Request $request
     *
     * @return Response
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function mapAction(Request $request = null)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Windfarm $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('Unable to find windfarm record with id: %s', $id));
        }

        return $this->render(
            ':Admin/Windfarm:map.html.twig',
            array(
                'action' => 'show',
                'object' => $object,
            )
        );
    }

    /**
     * Export Windmill blades from each Wind Farm in excel format action
     *
     * @param Request $request
     *
     * @return Response
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function excelAction(Request $request = null)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Windfarm $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('Unable to find windfarm record with id: %s', $id));
        }

        $form = $this->createForm(new WindfarmAnnualStatsFormType());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $audits = $this->getDoctrine()->getRepository('AppBundle:Audit')->getInvoicedOrDoneAuditsByWindfarmByYear($object, $form->get('year')->getData());

            /** @var Audit $audit */
            foreach ($audits as $audit) {
                $auditWindmillBlades = $audit->getAuditWindmillBlades();
                /** @var AuditWindmillBlade $auditWindmillBlade */
                foreach ($auditWindmillBlades as $auditWindmillBlade) {
                    $bladeDamages = $this->getDoctrine()->getRepository('AppBundle:BladeDamage')->getItemsOfAuditWindmillBladeSortedByRadius($auditWindmillBlade);
                    if (count($bladeDamages) > 0) {
                        $auditWindmillBlade->setBladeDamages($bladeDamages);
                    }
                }
            }

            $template = $this->renderView(
                ':Admin/Windfarm:excel.xls.twig',
                array(
                    'action' => 'show',
                    'windfarm' => $object,
                    'audits' => $audits,
                    'locale' => WindfarmLanguageEnum::getEnumArray()[$object->getLanguage()],
                )
            );

            $currentDate = new \DateTime();

            return new Response($template, 200, array(
                    'Content-type' => 'application/vnd.ms-excel',
                    'Content-Disposition' => 'attachment; filename="' . $currentDate->format('Y-m-d') . '_' . $object->getSlug() . '.xls"'
                )
            );
        }

        return $this->render(
            ':Admin/Windfarm:annual_stats.html.twig',
            array(
                'action' => 'show',
                'object' => $object,
                'form'   => $form->createView(),
            )
        );
    }
}
