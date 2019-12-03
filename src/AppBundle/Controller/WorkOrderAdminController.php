<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Windfarm;
use AppBundle\Entity\Windmill;
use AppBundle\Entity\WindmillBlade;
use AppBundle\Model\AjaxResponse;
use AppBundle\Repository\WindfarmRepository;
use AppBundle\Repository\WindmillBladeRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class WorkOrderAdminController.
 *
 * @category Controller
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 */
class WorkOrderAdminController extends AbstractBaseAdminController
{
    /**
     * @param int $id Customer ID
     *
     * @return JsonResponse
     */
    public function getWindfarmsFromCustomerIdAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        /** @var Customer $customer */
        $customer = $em->getRepository(Customer::class)->find($id);
        /** @var WindfarmRepository $wfr */
        $wfr = $em->getRepository(Windfarm::class);

        $ajaxResponse = new AjaxResponse();
        if (!$customer) {
            return new JsonResponse($ajaxResponse);
        }

        $ajaxResponse->setData($wfr->findCustomerEnabledSortedByNameAjax($customer));
        $jsonEncodedResult = $ajaxResponse->getJsonEncodedResult();

        return new JsonResponse($jsonEncodedResult);
    }

    /**
     * @param int $id Customer ID
     *
     * @return JsonResponse
     */
    public function getWindmillbladesFromWindmillIdAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        /** @var Windmill $windmill */
        $windmill = $em->getRepository(Windmill::class)->find($id);
        /** @var WindmillBladeRepository $wmbr */
        $wmbr = $em->getRepository(WindmillBlade::class);

        $ajaxResponse = new AjaxResponse();
        if (!$windmill) {
            return new JsonResponse($ajaxResponse);
        }
        $ajaxResponse->setData($wmbr->findWindmillSortedByCodeAjax($windmill));
        $jsonEncodedResult = $ajaxResponse->getJsonEncodedResult();

        return new JsonResponse($jsonEncodedResult);
    }
}
