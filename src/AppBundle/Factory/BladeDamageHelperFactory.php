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
     * @var BladeDamageHelper
     */
    private $bladeDamageHelper;

    /**
     * Methods
     */

    /**
     * BladeDamageHelperFactory constructor.
     *
     * @param AuditWindmillBlade $auditWindmillBlade
     * @param DamageCategory[]   $damageCategories
     *
     * @return BladeDamageHelper
     */
    public function create(AuditWindmillBlade $auditWindmillBlade, $damageCategories)
    {
        $this->bladeDamageHelper = new BladeDamageHelper();
        $this->bladeDamageHelper->setBlade($auditWindmillBlade->getWindmillBlade()->getOrder());
        /** @var DamageCategory $damageCategory */
        foreach ($damageCategories as $damageCategory) {
            $damageHelper = new DamageHelper();
            $damageHelper
                ->setNumber($damageCategory->getCategory())
                ->setColor($damageCategory->getColour())
            ;
            /** @var BladeDamage $bladeDamage */
            foreach ($auditWindmillBlade->getBladeDamages() as $bladeDamage) {
                if ($bladeDamage->getDamageCategory()->getId() == $damageCategory->getId()) {
                    $damageHelper->addDamage($bladeDamage->getNumber());

                    break;
                }
            }
            $this->bladeDamageHelper->addCategory($damageHelper);

        }
        /** @var BladeDamage $bladeDamage */
        foreach ($auditWindmillBlade->getBladeDamages() as $bladeDamage) {
            $this->bladeDamageHelper->addDamage($bladeDamage->getNumber().')');
        }

        return $this->bladeDamageHelper;
    }

    /**
     * @return BladeDamageHelper
     */
    public function getBladeDamageHelper()
    {
        return $this->bladeDamageHelper;
    }

    /**
     * @param BladeDamageHelper $bladeDamageHelper
     *
     * @return $this
     */
    public function setBladeDamageHelper(BladeDamageHelper $bladeDamageHelper)
    {
        $this->bladeDamageHelper = $bladeDamageHelper;

        return $this;
    }

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
