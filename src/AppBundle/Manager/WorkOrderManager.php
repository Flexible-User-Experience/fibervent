<?php


namespace AppBundle\Manager;


use AppBundle\Entity\Audit;
use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Entity\BladeDamage;
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
     * @param $audits Audit[]
     *
     * @return bool
     */
    public function checkIfAllAuditsBelongToOneWindfarm($audits)
    {
        //TODO make method

        return false;
    }
    /**
     * @param $audits Audit[]
     *
     * @return WorkOrder
     */
    public function createWorkOrderFromAudits($audits)
    {
        if ($audits->count()>0) {
            $workOrder = new WorkOrder();

            /** @var Audit $audit */
            foreach ($audits as $audit) {
                $auditWindmillBlades = $audit->getAuditWindmillBlades();
                if ($auditWindmillBlades->count()>0) {
                    /** @var AuditWindmillBlade $auditWindmillBlade */
                    foreach ($auditWindmillBlades as $auditWindmillBlade) {
                        $bladeDamages = $auditWindmillBlade->getBladeDamages();
                        if ($bladeDamages->count()>0) {
                            /** @var BladeDamage $bladeDamage */
                            foreach ($bladeDamages as $bladeDamage) {

                            }
                        }
                    }
                }
            }
        }


        return $workOrder;
    }

}
