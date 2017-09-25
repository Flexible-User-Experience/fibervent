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
 * Class WindfarmAdminController.
 *
 * @category Controller
 *
 * @author   David Romaní <david@flux.cat>
 */
class WindfarmAdminController extends AbstractBaseAdminController
{
    /**
     * Custom show action redirect to public frontend view.
     *
     * @param null $id
     *
     * @return Response
     *
     * @throws NotFoundHttpException     If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function showAction($id = null)
    {
        $request = $this->resolveRequest();
        $id = $request->get($this->admin->getIdParameter());

        /** @var Windfarm $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('Unable to find windfarm record with id: %s', $id));
        }

        // Customer filter
        if (!$this->get('app.auth_customer')->isWindfarmOwnResource($object)) {
            throw new AccessDeniedHttpException();
        }

        return $this->render(
            ':Admin/Windfarm:show.html.twig',
            array(
                'action' => 'show',
                'object' => $object,
            )
        );
    }

    /**
     * Show windfarm audits list view.
     *
     * @param Request|null $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException     If the object does not exist
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

        // Customer filter
        if (!$this->get('app.auth_customer')->isWindfarmOwnResource($object)) {
            throw new AccessDeniedHttpException();
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
     * Create windmills map view.
     *
     * @param Request|null $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException     If the object does not exist
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

        // Customer filter
        if (!$this->get('app.auth_customer')->isWindfarmOwnResource($object)) {
            throw new AccessDeniedHttpException();
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
     * Export Windmill blades from each Wind Farm in excel format action.
     * First step = display a year choice selector from audits.
     *
     * @param Request|null $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException     If the object does not exist
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

        // Customer filter
        if (!$this->get('app.auth_customer')->isWindfarmOwnResource($object)) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->createForm(WindfarmAnnualStatsFormType::class, null, array('windfarm_id' => $object->getId()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $damage_categories = null;
            if (array_key_exists('damage_categories', $request->get(WindfarmAnnualStatsFormType::BLOCK_PREFIX))) {
                $damage_categories = $request->get(WindfarmAnnualStatsFormType::BLOCK_PREFIX)['damage_categories'];
            }

            $statuses = null;
            if (array_key_exists('audit_status', $request->get(WindfarmAnnualStatsFormType::BLOCK_PREFIX))) {
                $statuses = $request->get(WindfarmAnnualStatsFormType::BLOCK_PREFIX)['audit_status'];
            }

            $year = intval($request->get(WindfarmAnnualStatsFormType::BLOCK_PREFIX)['year']);

            $audits = $this->getDoctrine()->getRepository('AppBundle:Audit')->getAuditsByWindfarmByStatusesAndYear(
                $object,
                $statuses,
                $year
            );

            /** @var Audit $audit */
            foreach ($audits as $audit) {
                $auditWindmillBlades = $audit->getAuditWindmillBlades();
                /** @var AuditWindmillBlade $auditWindmillBlade */
                foreach ($auditWindmillBlades as $auditWindmillBlade) {
                    $bladeDamages = $this->getDoctrine()->getRepository(
                        'AppBundle:BladeDamage'
                    )->getItemsOfAuditWindmillBladeSortedByRadius($auditWindmillBlade);
                    if (count($bladeDamages) > 0) {
                        $auditWindmillBlade->setBladeDamages($bladeDamages);
                    }
                }
            }

            return $this->render(
                ':Admin/Windfarm:annual_stats.html.twig',
                array(
                    'action' => 'show',
                    'object' => $object,
                    'form' => $form->createView(),
                    'damage_categories' => $damage_categories,
                    'year' => $year,
                    'audits' => $audits,
                    'show_download_xls_button' => true,
                )
            );
        }

        return $this->render(
            ':Admin/Windfarm:annual_stats.html.twig',
            array(
                'action' => 'show',
                'object' => $object,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Export Windmill blades from each Wind Farm in excel format action.
     * Second step = build an excel file and return as an attatchment response.
     *
     * @param Request|null $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException     If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function excelAttachmentAction(Request $request = null)
    {
        $statuses = null;
//        if (array_key_exists('audit_status', $request->query->get(WindfarmAnnualStatsFormType::BLOCK_PREFIX))) {
//            $statuses = $request->query->get(WindfarmAnnualStatsFormType::BLOCK_PREFIX)['audit_status'];
//        }
        $year = intval($request->get('year'));
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Windfarm $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('Unable to find windfarm record with id: %s', $id));
        }

        // Customer filter
        if (!$this->get('app.auth_customer')->isWindfarmOwnResource($object)) {
            throw new AccessDeniedHttpException();
        }

        $audits = $this->getDoctrine()->getRepository('AppBundle:Audit')->getAuditsByWindfarmByStatusesAndYear($object, $statuses, $year);

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

        $response = $this->render(
            ':Admin/Windfarm:excel.xls.twig',
            array(
                'action' => 'show',
                'windfarm' => $object,
                'audits' => $audits,
                'year' => $year,
                'locale' => WindfarmLanguageEnum::getEnumArray()[$object->getLanguage()],
            )
        );

        $currentDate = new \DateTime();
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$currentDate->format('Y-m-d').'_'.$object->getSlug().'.xls"');

        return $response;
    }
}
