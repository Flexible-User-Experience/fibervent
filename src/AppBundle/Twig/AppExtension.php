<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Damage;
use AppBundle\Entity\DamageCategory;
use AppBundle\Repository\DamageRepository;

/**
 * Class AppExtension
 *
 * @category Twig
 * @package  ECVulco\AppBundle\Twig
 * @author   David Romaní <david@flux.cat>
 */
class AppExtension extends \Twig_Extension
{
    /**
     * @var DamageRepository
     */
    private $dr;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * AppExtension constructor
     *
     * @param DamageRepository $dr
     */
    public function __construct(DamageRepository $dr)
    {
        $this->dr = $dr;
    }

    /**
     * Functions
     */

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_localized_description', array($this, 'getlocalizedDescription')),
            new \Twig_SimpleFunction('is_row_available', array($this, 'isRowAvailable')),
        );
    }

    /**
     * Get localized description from a Damage object
     *
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
     * Decide if a row is hidden or not by DamageCategory and an array of codes
     *
     * @param DamageCategory $object
     * @param array $availableCodes
     *
     * @return bool
     */
    public function isRowAvailable(DamageCategory $object, $availableCodes)
    {
        $result = false;
        if (in_array((string)$object->getCategory(), $availableCodes)) {
            $result = true;
        }

        return $result;
    }
}
