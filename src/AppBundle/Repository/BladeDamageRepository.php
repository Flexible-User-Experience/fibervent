<?php

namespace AppBundle\Repository;

use AppBundle\Entity\AuditWindmillBlade;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class BladeDamageRepository
 *
 * @category Repository
 * @package  AppBundle\Repository
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class BladeDamageRepository extends EntityRepository
{
    /**
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return QueryBuilder
     */
    public function getItemsOfAuditWindmillBladeSortedByRadiusQB(AuditWindmillBlade $auditWindmillBlade)
    {
        $query = $this
            ->createQueryBuilder('bd')
            ->where('bd.auditWindmillBlade = :awb')
            ->setParameter('awb', $auditWindmillBlade)
            ->orderBy('bd.radius', 'ASC');

        return $query;
    }

    /**
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return Query
     */
    public function getItemsOfAuditWindmillBladeSortedByRadiusQ(AuditWindmillBlade $auditWindmillBlade)
    {
        return $this->getItemsOfAuditWindmillBladeSortedByRadiusQB($auditWindmillBlade)->getQuery();
    }

    /**
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return array
     */
    public function getItemsOfAuditWindmillBladeSortedByRadius(AuditWindmillBlade $auditWindmillBlade)
    {
        return $this->getItemsOfAuditWindmillBladeSortedByRadiusQ($auditWindmillBlade)->getResult();
    }
}
