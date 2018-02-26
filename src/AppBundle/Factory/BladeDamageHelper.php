<?php

namespace AppBundle\Factory;

use AppBundle\Service\WindfarmAuditsPdfBuilderService;

/**
 * Class BladeDamageHelper.
 *
 * @category FactoryHelper
 *
 * @author   David Romaní <david@flux.cat>
 */
class BladeDamageHelper
{
    /**
     * @var int
     */
    private $blade;

    /**
     * @var array|CategoryDamageHelper[]
     */
    private $categories;

    /**
     * @var array|string[]
     */
    private $damages;

    /**
     * @var int
     */
    private $rowPdfHeight;

    /**
     * Methods.
     */

    /**
     * BladeDamageHelper constructor.
     */
    public function __construct()
    {
        $this->categories = array();
        $this->damages = array();
        $this->rowPdfHeight = WindfarmAuditsPdfBuilderService::DAMAGE_HEADER_HEIGHT_GENERAL_SUMMARY;
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
     * @return array|CategoryDamageHelper[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param array|CategoryDamageHelper[] $categories
     *
     * @return $this
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @param CategoryDamageHelper $category
     *
     * @return $this
     */
    public function addCategory(CategoryDamageHelper $category)
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
     * @param array|string[] $damages
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
        $this->rowPdfHeight = $this->rowPdfHeight + WindfarmAuditsPdfBuilderService::DAMAGE_HEADER_HEIGHT_GENERAL_SUMMARY;

        return $this;
    }

    /**
     * @return int
     */
    public function getRowPdfHeight()
    {
        return $this->rowPdfHeight == WindfarmAuditsPdfBuilderService::DAMAGE_HEADER_HEIGHT_GENERAL_SUMMARY ? WindfarmAuditsPdfBuilderService::DAMAGE_HEADER_HEIGHT_GENERAL_SUMMARY + 2 : $this->rowPdfHeight;
    }

    /**
     * @param int $rowPdfHeight
     *
     * @return $this
     */
    public function setRowPdfHeight($rowPdfHeight)
    {
        $this->rowPdfHeight = $rowPdfHeight;

        return $this;
    }
}
