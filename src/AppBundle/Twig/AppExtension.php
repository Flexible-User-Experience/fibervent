<?php

namespace AppBundle\Twig;

use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Entity\Damage;
use AppBundle\Entity\DamageCategory;
use AppBundle\Enum\AuditTypeEnum;
use AppBundle\Factory\WindmillBladesDamagesHelperFactory;
use AppBundle\Repository\DamageRepository;

/**
 * Class AppExtension.
 *
 * @category Twig
 *
 * @author   David Romaní <david@flux.cat>
 */
class AppExtension extends \Twig_Extension
{
    /**
     * @var DamageRepository
     */
    private $dr;

    /**
     * @var WindmillBladesDamagesHelperFactory
     */
    private $wbdhf;

    /**
     * Methods.
     */

    /**
     * AppExtension constructor.
     *
     * @param DamageRepository                   $dr
     * @param WindmillBladesDamagesHelperFactory $wbdhf
     */
    public function __construct(DamageRepository $dr, WindmillBladesDamagesHelperFactory $wbdhf)
    {
        $this->dr = $dr;
        $this->wbdhf = $wbdhf;
    }

    /**
     * Filters.
     */

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('humanized_audit_type', array($this, 'filterHumanizedAuditType')),
            new \Twig_SimpleFilter('get_humanized_total_hours', array($this, 'getHumanizedTotalHours')),
        );
    }

    /**
     * @param int $type
     *
     * @return string
     */
    public function filterHumanizedAuditType($type)
    {
        return AuditTypeEnum::getEnumArray()[$type];
    }

    /**
     * @param int|float|null $hours
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getHumanizedTotalHours($hours)
    {
        $result = '---';
        if (!is_null($hours)) {
            if (is_integer($hours) || is_float($hours)) {
                $whole = floor($hours);
                $fraction = $hours - $whole;
                $minutes = 0;
                if (0.25 == $fraction) {
                    $minutes = 15;
                } elseif (0.5 == $fraction) {
                    $minutes = 30;
                } elseif (0.75 == $fraction) {
                    $minutes = 45;
                }
                $interval = new \DateInterval(sprintf('PT%dH%dM', intval($hours), $minutes));
                $result = $interval->format('%H:%I');
            }
        }

        return $result;
    }

    /**
     * Functions.
     */

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_localized_description', array($this, 'getlocalizedDescription')),
            new \Twig_SimpleFunction('is_row_available', array($this, 'isRowAvailable')),
            new \Twig_SimpleFunction('mark_damage_category', array($this, 'markDamageCategory')),
        );
    }

    /**
     * @param Damage $object
     * @param string $locale
     *
     * @return string
     */
    public function getlocalizedDescription(Damage $object, $locale)
    {
        return $this->dr->getlocalizedDesciption($object->getId(), $locale);
    }

    /**
     * Decide if a row is hidden or not by DamageCategory and an array of codes.
     *
     * @param DamageCategory $object
     * @param array          $availableCodes
     *
     * @return bool
     */
    public function isRowAvailable(DamageCategory $object, $availableCodes)
    {
        $result = false;
        if (in_array((string) $object->getCategory(), $availableCodes)) {
            $result = true;
        }

        return $result;
    }

    /**
     * @param DamageCategory     $damageCategory
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return string
     */
    public function markDamageCategory(DamageCategory $damageCategory, AuditWindmillBlade $auditWindmillBlade)
    {
        return $this->wbdhf->markDamageCategory($damageCategory, $auditWindmillBlade);
    }
}
