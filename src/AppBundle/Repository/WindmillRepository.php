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
    public function findAllSortedByCustomerWindfarmTurbineQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->createQueryBuilder('w')
            ->orderBy('w.windfarm', $order)
//            ->addOrderBy('w.windfarm.name', $order)
            ->addOrderBy('w.turbine', $order);

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
    public function findAllSortedByCustomerWindfarmTurbineQ($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByCustomerWindfarmTurbineQB($limit, $order)->getQuery();
    }

    /**
     * @param null   $limit
     * @param string $order
     *
     * @return array
     */
    public function findAllSortedByCustomerWindfarmTurbine($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByCustomerWindfarmTurbineQ($limit, $order)->getResult();
    }
}
