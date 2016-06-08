<?php

namespace AppBundle\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * Class CountryRepository
 *
 * @category Repository
 * @package  AppBundle\Repository
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class CountryRepository extends EntityRepository
{
    /**
     * @param null   $limit
     * @param string $order
     *
     * @return QueryBuilder
     */
    public function findAllSortedByNameQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->createQueryBuilder('c')
            ->orderBy('c.name', $order);

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
    public function findAllSortedByNameQ($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByNameQB($limit, $order)->getQuery();
    }

    /**
     * @param null   $limit
     * @param string $order
     *
     * @return array
     */
    public function findAllSortedByName($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByNameQ($limit, $order)->getResult();
    }
}
