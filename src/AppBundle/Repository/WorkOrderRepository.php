<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class WorkOrderRepository.
 *
 * @category Repository
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 */
class WorkOrderRepository extends EntityRepository
{
    /**
     * @param null   $limit
     * @param string $order
     *
     * @return QueryBuilder
     */
    public function findAllSortedByProjectNumberQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->createQueryBuilder('t')
            ->orderBy('t.projectNumber', $order);

        if (!is_null($limit)) {
            $query->setMaxResults($limit);
        }

        return $query;
    }

    /**
     * @param null   $limit
     * @param string $order
     *
     * @return Query
     */
    public function findAllSortedByProjectNumberQ($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByProjectNumberQB($limit, $order)->getQuery();
    }

    /**
     * @param null   $limit
     * @param string $order
     *
     * @return array
     */
    public function findAllSortedByName($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByProjectNumberQ($limit, $order)->getResult();
    }
}
