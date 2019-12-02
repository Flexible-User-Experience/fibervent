<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Customer;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class WindfarmRepository.
 *
 * @category Repository
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class WindfarmRepository extends EntityRepository
{
    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return QueryBuilder
     */
    public function findAllSortedByNameQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->createQueryBuilder('w')
            ->orderBy('w.name', $order);

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
    public function findAllSortedByNameQ($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByNameQB($limit, $order)->getQuery();
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return array
     */
    public function findAllSortedByName($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByNameQ($limit, $order)->getResult();
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return QueryBuilder
     */
    public function findEnabledSortedByNameQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->findAllSortedByNameQB($limit, $order)
            ->where('w.enabled = true');

        return $query;
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return Query
     */
    public function findEnabledSortedByNameQ($limit = null, $order = 'ASC')
    {
        return $this->findEnabledSortedByNameQB($limit, $order)->getQuery();
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return array
     */
    public function findEnabledSortedByName($limit = null, $order = 'ASC')
    {
        return $this->findEnabledSortedByNameQ($limit, $order)->getResult();
    }

    /**
     * @param Customer $customer
     * @param int|null $limit
     * @param string   $order
     *
     * @return QueryBuilder
     */
    public function findOnlyAvailableSortedByNameQB(Customer $customer, $limit = null, $order = 'ASC')
    {
        $query = $this->findAllSortedByNameQB($limit, $order);
        $query
            ->where('w.customer IS NULL')
            ->orWhere('w.customer = :customer')
            ->setParameter('customer', $customer);

        return $query;
    }

    /**
     * @param Customer $customer
     * @param int|null $limit
     * @param string   $order
     *
     * @return Query
     */
    public function findOnlyAvailableSortedByNameQ(Customer $customer, $limit = null, $order = 'ASC')
    {
        return $this->findOnlyAvailableSortedByNameQB($customer, $limit, $order)->getQuery();
    }

    /**
     * @param Customer $customer
     * @param int|null $limit
     * @param string   $order
     *
     * @return array
     */
    public function findOnlyAvailableSortedByName(Customer $customer, $limit = null, $order = 'ASC')
    {
        return $this->findOnlyAvailableSortedByNameQ($customer, $limit, $order)->getResult();
    }

    /**
     * @param Customer $customer
     * @param int|null $limit
     * @param string   $order
     *
     * @return QueryBuilder
     */
    public function findCustomerEnabledSortedByNameQB(Customer $customer, $limit = null, $order = 'ASC')
    {
        return $this->findEnabledSortedByNameQB($limit, $order)
            ->andWhere('w.customer = :customer')
            ->setParameter('customer', $customer)
        ;
    }

    /**
     * @param Customer $customer
     * @param int|null $limit
     * @param string   $order
     *
     * @return Query
     */
    public function findCustomerEnabledSortedByNameQ(Customer $customer, $limit = null, $order = 'ASC')
    {
        return $this->findCustomerEnabledSortedByNameQB($customer, $limit, $order)->getQuery();
    }

    /**
     * @param Customer $customer
     * @param int|null $limit
     * @param string   $order
     *
     * @return array
     */
    public function findCustomerEnabledSortedByName(Customer $customer, $limit = null, $order = 'ASC')
    {
        return $this->findCustomerEnabledSortedByNameQ($customer, $limit, $order)->getResult();
    }

    /**
     * @param Customer $customer
     * @param int|null $limit
     * @param string   $order
     *
     * @return QueryBuilder
     */
    public function findCustomerEnabledSortedByNameAjaxQB(Customer $customer, $limit = null, $order = 'ASC')
    {
        return $this->findEnabledSortedByNameQB($limit, $order)
            ->select('w.name, w.id')
            ->andWhere('w.customer = :customer')
            ->setParameter('customer', $customer)
        ;
    }

    /**
     * @param Customer $customer
     * @param int|null $limit
     * @param string   $order
     *
     * @return Query
     */
    public function findCustomerEnabledSortedByNameAjaxQ(Customer $customer, $limit = null, $order = 'ASC')
    {
        return $this->findCustomerEnabledSortedByNameAjaxQB($customer, $limit, $order)->getQuery();
    }

    /**
     * @param Customer $customer
     * @param int|null $limit
     * @param string   $order
     *
     * @return array
     */
    public function findCustomerEnabledSortedByNameAjax(Customer $customer, $limit = null, $order = 'ASC')
    {
        return $this->findCustomerEnabledSortedByNameAjaxQ($customer, $limit, $order)->getResult();
    }
}
