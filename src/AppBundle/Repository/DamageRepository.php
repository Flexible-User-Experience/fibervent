<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class DamageRepository
 *
 * @category Repository
 * @package  AppBundle\Repository
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class DamageRepository extends EntityRepository
{
    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return QueryBuilder
     */
    public function findAllEnabledSortedByCodeQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->createQueryBuilder('d')
            ->orderBy('d.code', $order);

        if (!is_null($limit)) {
            $query->setMaxResults($limit);
        }

        return $query;
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return Query
     */
    public function findAllEnabledSortedByCodeQ($limit = null, $order = 'ASC')
    {
        return $this->findAllEnabledSortedByCodeQB($limit, $order)->getQuery();
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return array
     */
    public function findAllEnabledSortedByCode($limit = null, $order = 'ASC')
    {
        return $this->findAllEnabledSortedByCodeQ($limit, $order)->getResult();
    }
}
