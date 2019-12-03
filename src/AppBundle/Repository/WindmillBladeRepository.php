<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Windmill;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class WindmillBladeRepository.
 *
 * @category Repository
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class WindmillBladeRepository extends EntityRepository
{
    /**
     * @param Windmill $windmill
     * @param null     $limit
     * @param string   $order
     *
     * @return QueryBuilder
     */
    public function findWindmillSortedByCodeAjaxQB(Windmill $windmill, $limit = null, $order = 'ASC')
    {
        $query = $this
            ->createQueryBuilder('wb')
            ->select('wb.id, wb.code, wb.order')
            ->where('wb.windmill = :windmill')
            ->setParameter('windmill', $windmill)
            ->orderBy('wb.order', $order);

        if (!is_null($limit)) {
            $query->setMaxResults($limit);
        }

        return $query;
    }

    /**
     * @param Windmill $windmill
     * @param null     $limit
     * @param string   $order
     *
     * @return Query
     */
    public function findWindmillSortedByCodeAjaxQ(Windmill $windmill, $limit = null, $order = 'ASC')
    {
        return $this->findWindmillSortedByCodeAjaxQB($windmill, $limit, $order)->getQuery();
    }

    /**
     * @param Windmill $windmill
     * @param null     $limit
     * @param string   $order
     *
     * @return array
     */
    public function findWindmillSortedByCodeAjax(Windmill $windmill, $limit = null, $order = 'ASC')
    {
        return $this->findWindmillSortedByCodeAjaxQ($windmill, $limit, $order)->getResult();
    }
}
