<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Damage;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Translatable\TranslatableListener;

/**
 * Class DamageRepository
 *
 * @category Repository
 * @package  AppBundle\Repository
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class DamageRepository extends EntityRepository
{
    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return QueryBuilder
     */
    public function findAllEnabledSortedByCodeQB($limit = null, $order = 'ASC')
    {
        $query = $this
            ->createQueryBuilder('d')
            ->where('d.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('d.code', $order);

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
    public function findAllEnabledSortedByCodeQ($limit = null, $order = 'ASC')
    {
        return $this->findAllEnabledSortedByCodeQB($limit, $order)->getQuery();
    }

    /**
     * @param int|null $limit
     * @param string   $order
     *
     * @return array
     */
    public function findAllEnabledSortedByCode($limit = null, $order = 'ASC')
    {
        return $this->findAllEnabledSortedByCodeQ($limit, $order)->getResult();
    }

    /**
     * @param integer $id
     *
     * @return QueryBuilder
     */
    public function localizedFindQB($id)
    {
        $query = $this
            ->createQueryBuilder('d')
            ->where('d.id = :id')
            ->setParameter('id', $id);

        return $query;
    }

    /**
     * @param integer $id
     * @param string  $locale
     *
     * @return Query
     */
    public function localizedFindQ($id, $locale)
    {
        $query = $this->localizedFindQB($id)->getQuery();
        $query
            ->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
            ->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale)
            ->setHint(TranslatableListener::HINT_FALLBACK, 1);

        return $query;
    }

    /**
     * @param integer $id
     * @param string  $locale
     *
     * @return Damage
     */
    public function localizedFind($id, $locale)
    {
        return $this->localizedFindQ($id, $locale)->getOneOrNullResult();
    }
}
