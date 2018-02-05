<?php

namespace AppBundle\Factory;

/**
 * Class WindmillBladesDamagesHelper
 *
 * @category Factory
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
     * Methods
     */

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

        return $this;
    }
}
