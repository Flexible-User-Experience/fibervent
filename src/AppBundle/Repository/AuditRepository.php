<?php

namespace AppBundle\Repository;

use AppBundle\Enum\AuditStatusEnum;
use Doctrine\ORM\EntityRepository;

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
}
