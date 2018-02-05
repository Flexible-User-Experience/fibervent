<?php

namespace AppBundle\Factory;

/**
 * Class BladeDamageHelper
 *
 * @category Factory
 *
 * @author   David Romaní <david@flux.cat>
 */
class BladeDamageHelper
{
    /**
     * @var integer
     */
    private $blade;

    /**
     * @var array|DamageHelper[]
     */
    private $categories;

    /**
     * @var array
     */
    private $damages;

    /**
     * Methods
     */

    /**
     * BladeDamageHelper constructor.
     */
    public function __construct()
    {
        $this->categories = array();
        $this->damages = array();
    }

    /**
     * @return int
     */
    public function getBlade()
    {
        return $this->blade;
    }

    /**
     * @param int $blade
     *
     * @return $this
     */
    public function setBlade($blade)
    {
        $this->blade = $blade;

        return $this;
    }

    /**
     * @return array|DamageHelper[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param array|DamageHelper[] $categories
     *
     * @return $this
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @param DamageHelper $category
     *
     * @return $this
     */
    public function addCategory(DamageHelper $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * @return array
     */
    public function getDamages()
    {
        return $this->damages;
    }

    /**
     * @return string
     */
    public function getDamagesToString()
    {
        return implode('', $this->damages);
    }

    /**
     * @param array $damages
     *
     * @return $this
     */
    public function setDamages($damages)
    {
        $this->damages = $damages;

        return $this;
    }

    /**
     * @param string $damage
     *
     * @return $this
     */
    public function addDamage($damage)
    {
        $this->damages[] = $damage;

        return $this;
    }
}
