<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Audit;
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
     * @param int      $year
     *
     * @return QueryBuilder
     */
    public function getInvoicedOrDoneAuditsByWindfarmByYearQB(Windfarm $windfarm, $year)
    {
        $query = $this->getInvoicedOrDoneAuditsByWindfarmSortedByBeginDateQB($windfarm)
                ->andWhere('YEAR(a.beginDate) = :year')
                ->setParameter('year', $year);

        return $query;
    }

    /**
     * @param Windfarm $windfarm
     * @param int      $year
     *
     * @return Query
     */
    public function getInvoicedOrDoneAuditsByWindfarmByYearQ(Windfarm $windfarm, $year)
    {
        return $this->getInvoicedOrDoneAuditsByWindfarmByYearQB($windfarm, $year)->getQuery();
    }

    /**
     * @param Windfarm $windfarm
     * @param int      $year
     *
     * @return array
     */
    public function getInvoicedOrDoneAuditsByWindfarmByYear(Windfarm $windfarm, $year)
    {
        return $this->getInvoicedOrDoneAuditsByWindfarmByYearQ($windfarm, $year)->getResult();
    }

    /**
     * @param integer $windfarmId
     *
     * @return array
     */
    public function getYearsOfInvoicedOrDoneAuditsByWindfarm($windfarmId)
    {
        $query = $this->createQueryBuilder('a')
            ->select('YEAR(a.beginDate) AS year')
            ->where('a.windfarm = :windfarm')
            ->andWhere('a.status = :done OR a.status = :invoiced')
            ->setParameter('windfarm', $windfarmId)
            ->setParameter('done', AuditStatusEnum::DONE)
            ->setParameter('invoiced', AuditStatusEnum::INVOICED)
            ->orderBy('year', 'DESC')
            ->groupBy('year');

        $yearsArray = $query->getQuery()->getArrayResult();
        $choicesArray = array();
        foreach ($yearsArray as $year) {
            $value = $year['year'];
            $choicesArray["$value"] = intval($value);
        }

        return $choicesArray;
    }

    public function getYearChoices()
    {
        $query = $this->createQueryBuilder('a')
            ->orderBy('a.beginDate', 'ASC')
            ->setMaxResults(1);

        $audits = $query->getQuery()->getResult();
        if (count($audits) === 0) {
            return array('2016' => 2016);
        }

        /** @var Audit $firstAudit */
        $firstAudit = $audits[0];

        $yearsArray = array();
        $currentYear = new \DateTime();
        $currentYear = intval($currentYear->format('Y'));
        for ($firstYear = intval($firstAudit->getBeginDate()->format('Y')); $firstYear <= $currentYear; $firstYear++) {
            $yearsArray["$firstYear"] = $firstYear;
        }

        return $yearsArray;
    }
}
