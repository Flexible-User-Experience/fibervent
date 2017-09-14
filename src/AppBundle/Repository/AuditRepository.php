<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Audit;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Windfarm;
use AppBundle\Enum\AuditStatusEnum;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class AuditRepository.
 *
 * @category Repository
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class AuditRepository extends EntityRepository
{
    /**
     * @param int $status
     *
     * @return QueryBuilder
     */
    public function getDoingAuditsByStatusQB($status)
    {
        return $this->createQueryBuilder('a')
            ->where('a.status = :status')
            ->setParameter('status', $status);
    }

    /**
     * @return int
     */
    public function getDoingAuditsAmount()
    {
        $query = $this->getDoingAuditsByStatusQB(AuditStatusEnum::DOING)->getQuery();

        return count($query->getResult());
    }

    /**
     * @return int
     */
    public function getPendingAuditsAmount()
    {
        $query = $this->getDoingAuditsByStatusQB(AuditStatusEnum::PENDING)->getQuery();

        return count($query->getResult());
    }

    /**
     * @param Customer $customer
     *
     * @return int
     */
    public function getDoingAuditsByCustomerAmount(Customer $customer)
    {
        $query = $this->getDoingAuditsByStatusQB(AuditStatusEnum::DOING)
            ->andWhere('a.customer = :customer')
            ->setParameter('customer', $customer)
            ->getQuery();

        return count($query->getResult());
    }

    /**
     * @param Customer $customer
     *
     * @return int
     */
    public function getPendingAuditsByCustomerAmount(Customer $customer)
    {
        $query = $this->getDoingAuditsByStatusQB(AuditStatusEnum::PENDING)
            ->andWhere('a.customer = :customer')
            ->setParameter('customer', $customer)
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
            ->where('a.windfarm = :windfarm')
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
    public function getAllAuditsByWindfarmByYearQB(Windfarm $windfarm, $year)
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.windfarm = :windfarm')
            ->setParameter('windfarm', $windfarm)
            ->andWhere('YEAR(a.beginDate) = :year')
            ->setParameter('year', $year)
            ->orderBy('a.beginDate', 'DESC')
        ;

        return $query;
    }

    /**
     * @param Windfarm $windfarm
     * @param int      $year
     *
     * @return Query
     */
    public function getAllAuditsByWindfarmByYearQ(Windfarm $windfarm, $year)
    {
        return $this->getAllAuditsByWindfarmByYearQB($windfarm, $year)->getQuery();
    }

    /**
     * @param Windfarm $windfarm
     * @param int      $year
     *
     * @return array
     */
    public function getAllAuditsByWindfarmByYear(Windfarm $windfarm, $year)
    {
        return $this->getAllAuditsByWindfarmByYearQ($windfarm, $year)->getResult();
    }

    /**
     * @param Windfarm $windfarm
     * @param array    $statuses
     * @param int      $year
     *
     * @return QueryBuilder
     */
    public function getAuditsByWindfarmByStatusesAndYearQB(Windfarm $windfarm, $statuses, $year)
    {
        $query = $this->getAllAuditsByWindfarmByYearQB($windfarm, $year);
        if (!is_null($statuses)) {
            $filter = 'a.status = '.implode(' OR a.status = ', $statuses);
            $query->andWhere($filter);
        }

        return $query;
    }

    /**
     * @param Windfarm $windfarm
     * @param array    $statuses
     * @param int      $year
     *
     * @return Query
     */
    public function getAuditsByWindfarmByStatusesAndYearQ(Windfarm $windfarm, $statuses, $year)
    {
        return $this->getAuditsByWindfarmByStatusesAndYearQB($windfarm, $statuses, $year)->getQuery();
    }

    /**
     * @param Windfarm $windfarm
     * @param array    $statuses
     * @param int      $year
     *
     * @return array
     */
    public function getAuditsByWindfarmByStatusesAndYear(Windfarm $windfarm, $statuses, $year)
    {
        return $this->getAuditsByWindfarmByStatusesAndYearQ($windfarm, $statuses, $year)->getResult();
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
     * @param int $windfarmId
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

    /**
     * @param int $windfarmId
     *
     * @return array
     */
    public function getYearsOfAllAuditsByWindfarm($windfarmId)
    {
        $query = $this->createQueryBuilder('a')
            ->select('YEAR(a.beginDate) AS year')
            ->where('a.windfarm = :windfarm')
            ->setParameter('windfarm', $windfarmId)
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

    /**
     * @return array
     */
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

        $query = $this->createQueryBuilder('a')
            ->orderBy('a.beginDate', 'DESC')
            ->setMaxResults(1);

        $audits = $query->getQuery()->getResult();

        /** @var Audit $lastAudit */
        $lastAudit = $audits[0];

        $yearsArray = array();
        $firstYear = intval($firstAudit->getBeginDate()->format('Y'));
        $lastYear = intval($lastAudit->getBeginDate()->format('Y'));
        for ($currentYear = $lastYear; $currentYear >= $firstYear; --$currentYear) {
            $yearsArray["$currentYear"] = $currentYear;
        }

        return $yearsArray;
    }
}
