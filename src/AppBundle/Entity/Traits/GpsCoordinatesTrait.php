<?php

namespace AppBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Oh\GoogleMapFormTypeBundle\Validator\Constraints as OhAssert;

/**
 * GpsCoordinates trait
 *
 * @category Trait
 * @package  AppBundle\Entity\Traits
 * @author   Anton Serra <aserratorta@gmail.com>
 */
Trait GpsCoordinatesTrait
{
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
     *
     *
     * Methods
     *
     *
     */

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
     * @return $this
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
     * @return $this
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
}
