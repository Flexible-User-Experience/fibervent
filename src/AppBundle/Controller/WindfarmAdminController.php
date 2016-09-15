<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Windfarm;
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

        $audits = $this->getDoctrine()->getRepository('AppBundle:Audit')->getInvoicedOrDoneAuditsByWindfarmSortedByBeginDate($object);

        return $this->render(
            ':Admin/Windfarm:excel.xls.twig',
            array(
                'action' => 'show',
                'windfarm' => $object,
                'audits' => $audits,
            )
        );
    }
}
