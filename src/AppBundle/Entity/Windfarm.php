<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Windfarm
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WindfarmRepository")
 */
class Windfarm extends AbstractBase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=20)
     */
    private $gpsLongitude;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=20)
     */
    private $gpsLatitude;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $power;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @var State
     *
     * @ORM\ManyToOne(targetEntity="State", inversedBy="windfarms")
     */
    private $state;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="windfarms")
     */
    private $customer;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Windfarm
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Windfarm
     */
    public function setCity($city)
    {
        $this->city = $city;

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
     * @return Windfarm
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
     * @return Windfarm
     */
    public function setGpsLatitude($gpsLatitude)
    {
        $this->gpsLatitude = $gpsLatitude;

        return $this;
    }

    /**
     * @return int
     */
    public function getPower()
    {
        return $this->power;
    }

    /**
     * @param int $power
     * @return Windfarm
     */
    public function setPower($power)
    {
        $this->power = $power;

        return $this;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return Windfarm
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param State $state
     * @return Windfarm
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return Windfarm
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    public function __toString()
    {
        return $this->getName() ? $this->getName() : '---';
    }
}
