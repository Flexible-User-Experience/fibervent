<?php

namespace AppBundle\Factory;

/**
 * Class WindmillBladesDamagesHelper
 *
 * @category FactoryHelper
 *
 * @author   David Romaní <david@flux.cat>
 */
class WindmillBladesDamagesHelper
{
    /**
     * @var string
     */
    private $windmillShortCode;

    /**
     * @var array|BladeDamageHelper[]
     */
    private $bladeDamages;

    /**
     * @var integer
     */
    private $totalPdfHeight;

    /**
     * Methods
     */

    /**
     * WindmillBladesDamagesHelper constructor.
     *
     */
    public function __construct()
    {
        $this->bladeDamages = array();
        $this->totalPdfHeight = 0;
    }

    /**
     * @return string
     */
    public function getWindmillShortCode()
    {
        return $this->windmillShortCode;
    }

    /**
     * @param string $windmillShortCode
     *
     * @return $this
     */
    public function setWindmillShortCode(string $windmillShortCode)
    {
        $this->windmillShortCode = $windmillShortCode;

        return $this;
    }

    /**
     * @return BladeDamageHelper[]|array
     */
    public function getBladeDamages()
    {
        return $this->bladeDamages;
    }

    /**
     * @param BladeDamageHelper[]|array $bladeDamages
     *
     * @return $this
     */
    public function setBladeDamages($bladeDamages)
    {
        $this->bladeDamages = $bladeDamages;

        return $this;
    }

    /**
     * @param BladeDamageHelper $bladeDamageHelper
     *
     * @return $this
     */
    public function addBladeDamage(BladeDamageHelper $bladeDamageHelper)
    {
        $this->bladeDamages[] = $bladeDamageHelper;
        $this->totalPdfHeight = $this->totalPdfHeight + $bladeDamageHelper->getRowPdfHeight();

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalPdfHeight()
    {
        return $this->totalPdfHeight;
    }

    /**
     * @param int $totalPdfHeight
     *
     * @return $this
     */
    public function setTotalPdfHeight($totalPdfHeight)
    {
        $this->totalPdfHeight = $totalPdfHeight;

        return $this;
    }
}
