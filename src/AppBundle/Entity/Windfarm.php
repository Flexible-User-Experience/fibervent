<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CityTrait;
use AppBundle\Entity\Traits\GpsCoordinatesTrait;
use AppBundle\Entity\Traits\NameTrait;
use AppBundle\Entity\Traits\StateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
    use NameTrait;
    use StateTrait;
    use CityTrait;
    use GpsCoordinatesTrait;

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
     * @ORM\ManyToOne(targetEntity="State")
     */
    private $state;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="windfarms")
     */
    private $customer;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $manager;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Windmill", mappedBy="windfarm")
     */
    private $windmills;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * Windfarm constructor.
     */
    public function __construct()
    {
        $this->windmills = new ArrayCollection();
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
     *
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
     *
     * @return Windfarm
     */
    public function setYear($year)
    {
        $this->year = $year;

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
     *
     * @return Windfarm
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return User
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param User $manager
     *
     * @return Windfarm
     */
    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getWindmills()
    {
        return $this->windmills;
    }

    /**
     * @param ArrayCollection $windmills
     *
     * @return Windfarm
     */
    public function setWindmills(ArrayCollection $windmills)
    {
        $this->windmills = $windmills;

        return $this;
    }

    /**
     * @param Windmill $windmill
     *
     * @return $this
     */
    public function addWindmill(Windmill $windmill)
    {
        $windmill->setWindfarm($this);
        $this->windmills->add($windmill);

        return $this;
    }

    /**
     * @param Windmill $windmill
     *
     * @return $this
     */
    public function removeWindmill(Windmill $windmill)
    {
        $this->windmills->removeElement($windmill);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ? $this->getName() : '---';
    }
}
