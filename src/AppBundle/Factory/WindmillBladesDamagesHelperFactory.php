<?php

namespace AppBundle\Factory;

use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Entity\BladeDamage;
use AppBundle\Entity\DamageCategory;
use AppBundle\Entity\Audit;
use AppBundle\Repository\DamageCategoryRepository;

/**
 * Class WindmillBladesDamagesHelperFactory
 *
 * @category Factory
 *
 * @author   David Romaní <david@flux.cat>
 */
class WindmillBladesDamagesHelperFactory
{
    /**
     * @var DamageCategoryRepository
     */
    private $dcr;

    /**
     * Methods
     */

    /**
     * WindmillBladesDamagesHelperFactory constructor.
     *
     * @param DamageCategoryRepository $dcr
     */
    public function __construct(DamageCategoryRepository $dcr)
    {
        $this->dcr = $dcr;
    }

    /**
     * WindmillBladesDamagesHelperFactory builder.
     *
     * @param Audit $audit
     *
     * @return WindmillBladesDamagesHelper
     */
    public function buildWindfarmBladesDamagesHelper(Audit $audit)
    {
        $windfarmBladesDamagesHelper = new WindmillBladesDamagesHelper();
        $windfarmBladesDamagesHelper->setWindmillShortCode($audit->getWindmill()->getShortAutomatedCode());
        /** @var AuditWindmillBlade $auditWindmillBlade */
        foreach ($audit->getAuditWindmillBlades() as $auditWindmillBlade) {
            $bladeDamageHelper = new BladeDamageHelper();
            $bladeDamageHelper->setBlade($auditWindmillBlade->getWindmillBlade()->getOrder());
            /** @var DamageCategory $damageCategory */
            foreach ($this->dcr->findAllSortedByCategory() as $damageCategory) {
                $damageHelper = new DamageHelper();
                $damageHelper
                    ->setNumber($damageCategory->getCategory())
                    ->setColor($damageCategory->getColour())
                    ->setMark($this->markDamageCategory($damageCategory, $auditWindmillBlade))
                ;

                $bladeDamageHelper->addCategory($damageHelper);
            }

            $windfarmBladesDamagesHelper->addBladeDamage($bladeDamageHelper);
        }

        return $windfarmBladesDamagesHelper;
    }

    /**
     * BladeDamageHelperFactory constructor.
     *
     * @param AuditWindmillBlade $auditWindmillBlade
     * @param DamageCategory[]   $damageCategories
     *
     * @return BladeDamageHelper
     *
    public function create(AuditWindmillBlade $auditWindmillBlade, $damageCategories)
    {
        $this->bladeDamageHelper = new BladeDamageHelper();
        $this->bladeDamageHelper->setBlade($auditWindmillBlade->getWindmillBlade()->getOrder());
        $lettersRange = range('a', 'z');
        $index = 0;
        /** @var DamageCategory $damageCategory *
        foreach ($damageCategories as $damageCategory) {
            $damageHelper = new DamageHelper();
            $damageHelper
                ->setNumber($damageCategory->getCategory())
                ->setColor($damageCategory->getColour())
            ;
            /** @var BladeDamage $bladeDamage *
            foreach ($auditWindmillBlade->getBladeDamages() as $bladeDamage) {
                if ($bladeDamage->getDamageCategory()->getId() == $damageCategory->getId()) {
                    $damageHelper->addDamage($lettersRange[$index]);
                    $this->bladeDamageHelper->addDamage($bladeDamage->getGeneralSummaryDamageRowtoString($lettersRange[$index]));
                    $index++;
                }
            }
            $this->bladeDamageHelper->addCategory($damageHelper);
        }

        return $this->bladeDamageHelper;
    }*/

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
                $result = DamageHelper::MARK;

                break;
            }
        }

        return $result;
    }
}
