<?php

namespace AppBundle\Factory;

use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Entity\BladeDamage;
use AppBundle\Entity\DamageCategory;

/**
 * Class BladeDamageHelperFactory
 *
 * @category Factory
 *
 * @author   David Romaní <david@flux.cat>
 */
class BladeDamageHelperFactory
{
    /**
     * @param DamageCategory $damageCategory
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return string
     */
    public function markDamageCategory(DamageCategory $damageCategory, AuditWindmillBlade $auditWindmillBlade)
    {
        $result = '';
        /** @var BladeDamage $bladeDamage */
        foreach ($auditWindmillBlade->getBladeDamages() as $bladeDamage) {
            if ($bladeDamage->getDamageCategory()->getId() == $damageCategory->getId()) {
                $result = 'X';

                break;
            }
        }

        return $result;
    }
}
