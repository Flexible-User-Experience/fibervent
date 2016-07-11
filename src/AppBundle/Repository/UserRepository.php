<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Customer;
use AppBundle\Enum\UserRolesEnum;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class UserRepository
 *
 * @category Repository
 * @package  AppBundle\Repository
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class UserRepository extends EntityRepository
{
    /**
     * @param integer|null $limit
     * @param string       $order
     *
     * @return QueryBuilder
     */
    public function findAllSortedByNameQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->createQueryBuilder('u')
            ->orderBy('u.lastname', $order)
            ->addOrderBy('u.firstname', $order);

        if (!is_null($limit)) {
            $query->setMaxResults($limit);
        }

        return $query;
    }

    /**
     * @param integer|null $limit
     * @param string       $order
     *
     * @return Query
     */
    public function findAllSortedByNameQ($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByNameQB($limit, $order)->getQuery();
    }

    /**
     * @param integer|null $limit
     * @param string       $order
     *
     * @return array
     */
    public function findAllSortedByName($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByNameQ($limit, $order)->getResult();
    }

    /**
     * @param Customer|null $customer
     * @param integer|null  $limit
     * @param string        $order
     *
     * @return QueryBuilder
     */
    public function findOnlyAvailableSortedByNameQB($customer, $limit = null, $order = 'ASC')
    {
        $query = $this->findAllSortedByNameQB($limit, $order);
        $query
            ->where('u.customer IS NULL')
            ->orWhere('u.customer = :customer')
            ->setParameter('customer', $customer);

        return $query;
    }

    /**
     * @param Customer|null $customer
     * @param integer|null  $limit
     * @param string        $order
     *
     * @return Query
     */
    public function findOnlyAvailableSortedByNameQ($customer, $limit = null, $order = 'ASC')
    {
        return $this->findOnlyAvailableSortedByNameQB($customer, $limit, $order)->getQuery();
    }

    /**
     * @param Customer|null $customer
     * @param integer|null  $limit
     * @param string        $order
     *
     * @return array
     */
    public function findOnlyAvailableSortedByName($customer, $limit = null, $order = 'ASC')
    {
        return $this->findOnlyAvailableSortedByNameQ($customer, $limit, $order)->getResult();
    }

    /**
     * @param integer|null $limit
     * @param string       $order
     *
     * @return QueryBuilder
     */
    public function findAllTechnicinasSortedByNameQB($limit = null, $order = 'ASC')
    {
        return $this
            ->findAllSortedByNameQB($limit, $order)
            ->where('u.roles NOT LIKE :role')
            ->setParameter('role', '%' . UserRolesEnum::ROLE_CUSTOMER . '%');
    }

    /**
     * @param integer|null $limit
     * @param string       $order
     *
     * @return Query
     */
    public function findAllTechnicinasSortedByNameQ($limit = null, $order = 'ASC')
    {
        return $this->findAllTechnicinasSortedByNameQB($limit, $order)->getQuery();
    }

    /**
     * @param integer|null $limit
     * @param string       $order
     *
     * @return array
     */
    public function findAllTechnicinasSortedByName($limit = null, $order = 'ASC')
    {
        return $this->findAllTechnicinasSortedByNameQ($limit, $order)->getResult();
    }

    /**
     * @param Customer|null $customer
     * @param integer|null  $limit
     * @param string        $order
     *
     * @return QueryBuilder
     */
    public function findEnabledSortedByNameQB($customer, $limit = null, $order = 'ASC')
    {
        $query = $this
            ->findAllSortedByNameQB($limit, $order)
            ->where('u.enabled = true AND u.customer IS NULL')
            ->orWhere('u.enabled = true AND u.customer = :customer')
            ->setParameter('customer', $customer);

        return $query;
    }

    /**
     * @param Customer|null $customer
     * @param integer|null  $limit
     * @param string        $order
     *
     * @return Query
     */
    public function findEnabledSortedByNameQ($customer, $limit = null, $order = 'ASC')
    {
        return $this->findEnabledSortedByNameQB($customer, $limit, $order)->getQuery();
    }

    /**
     * @param Customer|null $customer
     * @param integer|null  $limit
     * @param string        $order
     *
     * @return array
     */
    public function findEnabledSortedByName($customer, $limit = null, $order = 'ASC')
    {
        return $this->findEnabledSortedByNameQ($customer, $limit, $order)->getResult();
    }
}
