<?php

namespace AppBundle\Repository;

use AppBundle\Entity\AuditWindmillBlade;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ObservationRepository.
 *
 * @category Repository
 *
 * @author   David Romaní <david@flux.cat>
 */
class ObservationRepository extends EntityRepository
{
    /**
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return QueryBuilder
     */
    public function getItemsOfAuditWindmillBladeSortedByDamageNumberQB(AuditWindmillBlade $auditWindmillBlade)
    {
        $query = $this
            ->createQueryBuilder('o')
            ->where('o.auditWindmillBlade = :awb')
            ->setParameter('awb', $auditWindmillBlade)
            ->orderBy('o.damageNumber', 'ASC');

        return $query;
    }

    /**
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return Query
     */
    public function getItemsOfAuditWindmillBladeSortedByDamageNumberQ(AuditWindmillBlade $auditWindmillBlade)
    {
        return $this->getItemsOfAuditWindmillBladeSortedByDamageNumberQB($auditWindmillBlade)->getQuery();
    }

    /**
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return array
     */
    public function getItemsOfAuditWindmillBladeSortedByDamageNumber(AuditWindmillBlade $auditWindmillBlade)
    {
        return $this->getItemsOfAuditWindmillBladeSortedByDamageNumberQ($auditWindmillBlade)->getResult();
    }
}
