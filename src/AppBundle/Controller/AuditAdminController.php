<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Audit;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
     * @param int|string|null $id
     * @param Request $request
     *
     * @return Response
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function pdfAction($id = null, Request $request = null)
    {
//        $request = $this->resolveRequest($request);
//        $id = $request->get($this->admin->getIdParameter());

        /** @var Audit $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }
    }
}
