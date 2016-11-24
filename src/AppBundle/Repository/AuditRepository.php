<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Windfarm;
use AppBundle\Enum\AuditStatusEnum;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class AuditRepository
 *
 * @category Repository
 * @package  AppBundle\Repository
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class AuditRepository extends EntityRepository
{
    /**
     * @return int
     */
    public function getDoingAuditsAmount()
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.status = :status' )
            ->setParameter('status', AuditStatusEnum::DOING)
            ->getQuery();

        return count($query->getResult());
    }

    /**
     * @return int
     */
    public function getPendingAuditsAmount()
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.status = :status')
            ->setParameter('status', AuditStatusEnum::PENDING)
            ->getQuery();

        return count($query->getResult());
    }

    /**
     * @param Windfarm $windfarm
     *
     * @return QueryBuilder
     */
    public function getInvoicedOrDoneAuditsByWindfarmSortedByBeginDateQB(Windfarm $windfarm)
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.windfarm = :windfarm' )
            ->andWhere('a.status = :done OR a.status = :invoiced')
            ->setParameter('windfarm', $windfarm)
            ->setParameter('done', AuditStatusEnum::DONE)
            ->setParameter('invoiced', AuditStatusEnum::INVOICED)
            ->orderBy('a.beginDate', 'DESC');

        return $query;
    }

    /**
     * @param Windfarm $windfarm
     *
     * @return Query
     */
    public function getInvoicedOrDoneAuditsByWindfarmSortedByBeginDateQ(Windfarm $windfarm)
    {
        return $this->getInvoicedOrDoneAuditsByWindfarmSortedByBeginDateQB($windfarm)->getQuery();
    }

    /**
     * @param Windfarm $windfarm
     *
     * @return array
     */
    public function getInvoicedOrDoneAuditsByWindfarmSortedByBeginDate(Windfarm $windfarm)
    {
        return $this->getInvoicedOrDoneAuditsByWindfarmSortedByBeginDateQ($windfarm)->getResult();
    }

    /**
     * @param Windfarm $windfarm
     *
     * @return array
     */
    public function getInvoicedOrDoneAuditsByWindfarmByYear(Windfarm $windfarm)
    {
        $query = $this->getInvoicedOrDoneAuditsByWindfarmSortedByBeginDateQB($windfarm)
                ->andWhere('a.beginDate ');


        return $query->getResult();
    }
}
