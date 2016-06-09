<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Audit;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ps\PdfBundle\Annotation\Pdf;

/**
 * Class AuditAdminController
 *
 * @category Controller
 * @package  AppBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
class AuditAdminController extends Controller
{
    /**
     * Export Audit in PDF format action
     *
     * @Pdf()
     *
     * @param int $id
     *
     * @return Response
     * @throws NotFoundHttpException If the object does not exist
     */
    public function pdfAction($id)
    {
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
}
