<?php


namespace AppBundle\Manager;


use AppBundle\Entity\WorkOrder;
use Doctrine\ORM\EntityManagerInterface;

class WorkOrderManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ObservationManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * @param $audits
     *
     * @return WorkOrder
     */
    public function createWorkOrderFromAudits($audits)
    {
        $workOrder = new WorkOrder();

        return $workOrder;
    }

}
