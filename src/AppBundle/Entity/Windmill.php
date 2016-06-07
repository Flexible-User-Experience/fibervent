<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Oh\GoogleMapFormTypeBundle\Validator\Constraints as OhAssert;

/**
 * Windmill
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WindmillRepository")
 * @UniqueEntity("code")
 */
class Windmill extends AbstractBase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique = true)
     */
    private $code;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=20)
     */
    private $gpsLongitude = 0.716726;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=20)
     */
    private $gpsLatitude = 40.881604;

    /**
     * @var Windfarm
     *
     * @ORM\ManyToOne(targetEntity="Windfarm", inversedBy="windmills")
     */
    private $windfarm;

    /**
     * @var Turbine
     *
     * @ORM\ManyToOne(targetEntity="Turbine", inversedBy="windmills")
     */
    private $turbine;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Windmill
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return float
     */
    public function getGpsLongitude()
    {
        return $this->gpsLongitude;
    }

    /**
     * @param float $gpsLongitude
     *
     * @return Windmill
     */
    public function setGpsLongitude($gpsLongitude)
    {
        $this->gpsLongitude = $gpsLongitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getGpsLatitude()
    {
        return $this->gpsLatitude;
    }

    /**
     * @param float $gpsLatitude
     *
     * @return Windmill
     */
    public function setGpsLatitude($gpsLatitude)
    {
        $this->gpsLatitude = $gpsLatitude;

        return $this;
    }

    /**
     * Get LatLng
     *
     * @Assert\NotBlank()
     * @OhAssert\LatLng()
     *
     * @return array
     */
    public function getLatLng()
    {
        return array(
            'lat' => $this->getGpsLatitude(),
            'lng' => $this->getGpsLongitude(),
        );
    }

    /**
     * Set LatLng
     *
     * @param array $latlng
     *
     * @return $this
     */
    public function setLatLng($latlng)
    {
        $this->setGpsLatitude($latlng['lat']);
        $this->setGpsLongitude($latlng['lng']);

        return $this;
    }

    /**
     * @return Windfarm
     */
    public function getWindfarm()
    {
        return $this->windfarm;
    }

    /**
     * @param Windfarm $windfarm
     *
     * @return Windmill
     */
    public function setWindfarm($windfarm)
    {
        $this->windfarm = $windfarm;

        return $this;
    }

    /**
     * @return Turbine
     */
    public function getTurbine()
    {
        return $this->turbine;
    }

    /**
     * @param Turbine $turbine
     *
     * @return Windmill
     */
    public function setTurbine($turbine)
    {
        $this->turbine = $turbine;

        return $this;
    }
}
