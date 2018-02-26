<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Translatable\TranslatableListener;

/**
 * Class DamageCategoryRepository.
 *
 * @category Repository
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class DamageCategoryRepository extends EntityRepository
{
    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return QueryBuilder
     */
    public function findAllSortedByCategoryQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->createQueryBuilder('dc')
            ->orderBy('dc.category', $order);

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
    public function findAllSortedByCategoryQ($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByCategoryQB($limit, $order)->getQuery();
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return array
     */
    public function findAllSortedByCategory($limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByCategoryQ($limit, $order)->getResult();
    }

    /**
     * @param string   $locale
     * @param int|null $limit
     * @param string   $order
     *
     * @return Query
     */
    public function findAllSortedByCategoryLocalizedQ($locale, $limit = null, $order = 'ASC')
    {
        $query = $this->findAllSortedByCategoryQB($limit, $order)->getQuery();
        $query
            ->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
            ->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale)
            ->setHint(TranslatableListener::HINT_FALLBACK, 1);

        return $query;
    }

    /**
     * @param string   $locale
     * @param int|null $limit
     * @param string   $order
     *
     * @return array
     */
    public function findAllSortedByCategoryLocalized($locale, $limit = null, $order = 'ASC')
    {
        return $this->findAllSortedByCategoryLocalizedQ($locale, $limit, $order)->getResult();
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return QueryBuilder
     */
    public function findEnabledSortedByCategoryQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->findAllSortedByCategoryQB($limit, $order)
            ->where('dc.enabled = true');

        return $query;
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return Query
     */
    public function findEnabledSortedByCategoryQ($limit = null, $order = 'ASC')
    {
        return $this->findEnabledSortedByCategoryQB($limit, $order)->getQuery();
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return array
     */
    public function findEnabledSortedByCategory($limit = null, $order = 'ASC')
    {
        return $this->findEnabledSortedByCategoryQ($limit, $order)->getResult();
    }
}
