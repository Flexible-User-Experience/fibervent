<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Damage;
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
}
