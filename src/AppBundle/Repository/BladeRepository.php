<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class BladeRepository.
 *
 * @category Repository
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class BladeRepository extends EntityRepository
{
    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return QueryBuilder
     */
    public function findAllSortedByModelQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->createQueryBuilder('b')
            ->orderBy('b.model', $order);

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
    public function findAllSortedByModelQ($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByModelQB($limit, $order)->getQuery();
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return array
     */
    public function findAllSortedByModel($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByModelQ($limit, $order)->getResult();
    }
}
