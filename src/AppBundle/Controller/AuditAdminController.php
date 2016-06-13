<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Audit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ps\PdfBundle\Annotation\Pdf;

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
     * @Pdf()
     *
     * @param Request         $request
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
            throw $this->createNotFoundException(sprintf('Unable to find audit record with id : %s', $id));
        }

        return $this->render(
            ':PDF:audit.pdf.twig',
            array(
                'audit' => $object,
            )
        );
    }

    /**
     * Custom show action redirect to public frontend view
     *
     * @param Request         $request
     *
     * @return Response
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function showAction(Request $request = null)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Audit $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('Unable to find audit record with id : %s', $id));
        }

        return $this->render(
            ':Admin/Audit:show.html.twig',
            array(
                'action'   => 'show',
                'object' => $object,
            )
        );
    }
}
