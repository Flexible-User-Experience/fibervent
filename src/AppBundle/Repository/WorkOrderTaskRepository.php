<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class WorkOrderTaskRepository.
 *
 * @category Repository
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 */
class WorkOrderTaskRepository extends EntityRepository
{
    /**
     * @param null   $limit
     * @param string $order
     *
     * @return QueryBuilder
     */
    public function findAllSortedByIdQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->createQueryBuilder('t')
            ->orderBy('t.id', $order);

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
    public function findAllSortedByIdQ($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByIdQB($limit, $order)->getQuery();
    }

    /**
     * @param null   $limit
     * @param string $order
     *
     * @return array
     */
    public function findAllSortedById($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByIdQ($limit, $order)->getResult();
    }
}
