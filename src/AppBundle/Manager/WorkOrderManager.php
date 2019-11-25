<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Audit;
use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Entity\BladeDamage;
use AppBundle\Entity\WorkOrder;
use AppBundle\Entity\WorkOrderTask;
use Doctrine\ORM\EntityManager;

class WorkOrderManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * ObservationManager constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $audits Audit[]
     *
     * @return bool
     */
    public function checkIfAllAuditsBelongToOneWindfarm($audits)
    {
        $check = true;
        $windfarm = $audits[0]->getWindfarm();
        foreach ($audits as $audit) {
            if ($audit->getWindfarm() != $windfarm) {
                $check = false;
            }
        }

        return $check;
    }

    /**
     * @param $audits Audit[]
     *
     * @return WorkOrder
     */
    public function createWorkOrderFromAudits($audits)
    {
        $workOrder = new WorkOrder();
        $workOrder->setCustomer($audits[0]->getCustomer());
        $workOrder->setIsFromAudit(true);
        $workOrder->setWindfarm($audits[0]->getWindfarm());
        $this->em->persist($workOrder);
        /** @var Audit $audit */
        foreach ($audits as $audit) {
            $auditWindmillBlades = $audit->getAuditWindmillBlades();
            //TODO set each audit as realted entity in $workOrder after making relation ManyToMany
            if ($auditWindmillBlades->count() > 0) {
                /** @var AuditWindmillBlade $auditWindmillBlade */
                foreach ($auditWindmillBlades as $auditWindmillBlade) {
                    $bladeDamages = $auditWindmillBlade->getBladeDamages();
                    if ($bladeDamages->count() > 0) {
                        /** @var BladeDamage $bladeDamage */
                        foreach ($bladeDamages as $bladeDamage) {
                            $workOrderTask = new WorkOrderTask();
                            $workOrderTask->setWorkOrder($workOrder);
                            $workOrderTask->setIsFromAudit(true);
                            $workOrderTask->setBladeDamage($bladeDamage);
                            $workOrderTask->setDescription($bladeDamage->getDamage()->getDescription());
                            $workOrderTask->setWindmillBlade($bladeDamage->getAuditWindmillBlade()->getWindmillBlade());
                            $workOrderTask->setWindmill($bladeDamage->getAuditWindmillBlade()->getWindmillBlade()->getWindmill());
                            $workOrderTask->setPosition($bladeDamage->getPosition());
                            $workOrderTask->setRadius($bladeDamage->getRadius());
                            $workOrderTask->setDistance($bladeDamage->getDistance());
                            $workOrderTask->setSize($bladeDamage->getSize());
                            $workOrderTask->setEdge($bladeDamage->getEdge());
                            $workOrderTask->setIsCompleted(false);
                            $this->em->persist($workOrderTask);
                        }
                    }
                }
            }
        }
        $this->em->persist($workOrder);
        $this->em->flush();

        return $workOrder;
    }
}
