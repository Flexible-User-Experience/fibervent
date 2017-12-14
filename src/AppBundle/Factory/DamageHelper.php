<?php

namespace AppBundle\Factory;

/**
 * Class DamageHelper
 *
 * @category Factory
 *
 * @author   David Romaní <david@flux.cat>
 */
class DamageHelper
{
    /**
     * @var integer
     */
    private $number;

    /**
     * @var string
     */
    private $color;

    /**
     * @var string
     */
    private $mark;

    /**
     * @var array
     */
    private $damages;

    /**
     * Methods
     */

    /**
     * DamageHelper constructor.
     */
    public function __construct()
    {
        $this->mark = '';
        $this->damages = array();
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     *
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return $this
     */
    public function setColor(string $color)
    {
        $this->color = $color;

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
        return implode(', ', $this->damages);
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
     * @param $damage
     *
     * @return $this
     */
    public function addDamage($damage)
    {
        $this->damages[] = $damage;
        $this->mark = 'X';

        return $this;
    }
}
