<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Customer;
use AppBundle\Enum\UserRolesEnum;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class UserRepository.
 *
 * @category Repository
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class UserRepository extends EntityRepository
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
            ->createQueryBuilder('u')
            ->orderBy('u.lastname', $order)
            ->addOrderBy('u.firstname', $order);

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
     * @param Customer|null $customer
     * @param int|null      $limit
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
     * @param int|null      $limit
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
     * @param int|null      $limit
     * @param string        $order
     *
     * @return array
     */
    public function findOnlyAvailableSortedByName($customer, $limit = null, $order = 'ASC')
    {
        return $this->findOnlyAvailableSortedByNameQ($customer, $limit, $order)->getResult();
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return QueryBuilder
     */
    public function findAllTechniciansSortedByNameQB($limit = null, $order = 'ASC')
    {
        return $this
            ->findAllSortedByNameQB($limit, $order)
            ->where('u.roles NOT LIKE :role')
            ->andWhere('u.enabled = true')
            ->setParameter('role', '%'.UserRolesEnum::ROLE_CUSTOMER.'%');
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return Query
     */
    public function findAllTechniciansSortedByNameQ($limit = null, $order = 'ASC')
    {
        return $this->findAllTechniciansSortedByNameQB($limit, $order)->getQuery();
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return array
     */
    public function findAllTechniciansSortedByName($limit = null, $order = 'ASC')
    {
        return $this->findAllTechniciansSortedByNameQ($limit, $order)->getResult();
    }

    /**
     * @param Customer|null $customer
     * @param int|null      $limit
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
     * @param int|null      $limit
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
     * @param int|null      $limit
     * @param string        $order
     *
     * @return array
     */
    public function findEnabledSortedByName($customer, $limit = null, $order = 'ASC')
    {
        return $this->findEnabledSortedByNameQ($customer, $limit, $order)->getResult();
    }

    /**
     * @param Customer $customer
     * @param int|null $limit
     * @param string   $order
     *
     * @return QueryBuilder
     */
    public function findRegionalManagersByCustomerQB(Customer $customer, $limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByNameQB($limit, $order)
            ->where('u.customer = :customer')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('customer', $customer)
            ->setParameter('role', '%'.UserRolesEnum::ROLE_CUSTOMER.'%')
        ;
    }

    /**
     * @param Customer $customer
     * @param int|null $limit
     * @param string   $order
     *
     * @return Query
     */
    public function findRegionalManagersByCustomerQ(Customer $customer, $limit = null, $order = 'ASC')
    {
        return $this->findRegionalManagersByCustomerQB($customer, $limit, $order)->getQuery();
    }

    /**
     * @param Customer $customer
     * @param int|null $limit
     * @param string   $order
     *
     * @return array
     */
    public function findRegionalManagersByCustomer(Customer $customer, $limit = null, $order = 'ASC')
    {
        return $this->findRegionalManagersByCustomerQ($customer, $limit, $order)->getResult();
    }
}
