<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class WindmillRepository
 *
 * @category Repository
 * @package  AppBundle\Repository
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class WindmillRepository extends EntityRepository
{
    /**
     * @param null   $limit
     * @param string $order
     *
     * @return QueryBuilder
     */
    public function findAllSortedByCustomerWindfarmAndWindmillCodeQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->createQueryBuilder('windmill')
            ->select('windmill, windfarm, customer')
            ->join('windmill.windfarm', 'windfarm')
            ->join('windfarm.customer', 'customer')
            ->orderBy('customer.name', $order)
            ->addOrderBy('windfarm.name', $order)
            ->addOrderBy('windmill.code', $order);

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
    public function findAllSortedByCustomerWindfarmAndWindmillCodeQ($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByCustomerWindfarmAndWindmillCodeQB($limit, $order)->getQuery();
    }

    /**
     * @param null   $limit
     * @param string $order
     *
     * @return array
     */
    public function findAllSortedByCustomerWindfarmAndWindmillCode($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByCustomerWindfarmAndWindmillCodeQ($limit, $order)->getResult();
    }

    /**
     * @param null   $limit
     * @param string $order
     *
     * @return QueryBuilder
     */
    public function findEnabledSortedByCustomerWindfarmAndWindmillCodeQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->findAllSortedByCustomerWindfarmAndWindmillCodeQB($limit, $order)
            ->where('windmill.enabled = true');

        return $query;
    }

    /**
     * @param null   $limit
     * @param string $order
     *
     * @return Query
     */
    public function findEnabledSortedByCustomerWindfarmAndWindmillCodeQ($limit = null, $order = 'ASC')
    {
        return $this->findEnabledSortedByCustomerWindfarmAndWindmillCodeQB($limit, $order)->getQuery();
    }

    /**
     * @param null   $limit
     * @param string $order
     *
     * @return array
     */
    public function findEnabledSortedByCustomerWindfarmAndWindmillCode($limit = null, $order = 'ASC')
    {
        return $this->findEnabledSortedByCustomerWindfarmAndWindmillCodeQ($limit, $order)->getResult();
    }
}
