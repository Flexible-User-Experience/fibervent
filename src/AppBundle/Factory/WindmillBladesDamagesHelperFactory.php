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
    public function buildWindmillBladesDamagesHelper(Audit $audit)
    {
        $lettersRange = range('a', 'z');
        $index = 0;

        $windmillBladesDamagesHelper = new WindmillBladesDamagesHelper();
        $windmillBladesDamagesHelper->setWindmillShortCode($audit->getWindmill()->getShortAutomatedCode());

        /** @var AuditWindmillBlade $auditWindmillBlade */
        foreach ($audit->getAuditWindmillBlades() as $auditWindmillBlade) {

            $bladeDamageHelper = new BladeDamageHelper();
            $bladeDamageHelper->setBlade($auditWindmillBlade->getWindmillBlade()->getOrder());

            /** @var DamageCategory $damageCategory */
            foreach ($this->dcr->findAllSortedByCategory() as $damageCategory) {

                $categoryDamageHelper = new CategoryDamageHelper();
                $categoryDamageHelper
                    ->setNumber($damageCategory->getCategory())
                    ->setColor($damageCategory->getColour())
                ;

                /** @var BladeDamage $bladeDamage */
                foreach ($auditWindmillBlade->getBladeDamages() as $bladeDamage) {
                    if ($bladeDamage->getDamageCategory()->getId() == $damageCategory->getId()) {
                        $categoryDamageHelper->addLetterMark($lettersRange[$index]);
                        $bladeDamageHelper->addDamage($bladeDamage->getGeneralSummaryDamageRowtoString($lettersRange[$index]));
                        $index++;
                    }
                }

                $bladeDamageHelper->addCategory($categoryDamageHelper);
            }

            $windmillBladesDamagesHelper->addBladeDamage($bladeDamageHelper);
        }

        return $windmillBladesDamagesHelper;
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
                $result = CategoryDamageHelper::MARK;

                break;
            }
        }

        return $result;
    }
}
