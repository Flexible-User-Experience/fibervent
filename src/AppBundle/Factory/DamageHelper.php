<?php

namespace AppBundle\Factory;

use AppBundle\Service\WindfarmAuditsPdfBuilderService;

/**
 * Class DamageHelper
 *
 * @category Factory
 *
 * @author   David Romaní <david@flux.cat>
 */
class DamageHelper
{
    const MARK = 'X';

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
     * @var integer
     */
    private $pdfHeight = 0;

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
     * @return string
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * @param string $mark
     *
     * @return $this
     */
    public function setMark($mark)
    {
        $this->mark = $mark;

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
    public function getDamagesToLettersRangeString()
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
     * @param string $damage
     *
     * @return $this
     */
    public function addDamage($damage)
    {
        $this->damages[] = $damage;
        $this->mark = self::MARK;
        $this->pdfHeight = $this->pdfHeight + WindfarmAuditsPdfBuilderService::DAMAGE_HEADER_HEIGHT_GENERAL_SUMMARY;

        return $this;
    }

    /**
     * @return int
     */
    public function getPdfHeight()
    {
        return $this->pdfHeight;
    }

    /**
     * @param int $pdfHeight
     *
     * @return $this
     */
    public function setPdfHeight(int $pdfHeight)
    {
        $this->pdfHeight = $pdfHeight;

        return $this;
    }
}
