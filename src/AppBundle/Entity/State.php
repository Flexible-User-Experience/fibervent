<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * State
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StateRepository")
 */
class State extends AbstractBase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="states")
     */
    private $country;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Customer", mappedBy="state")
     */
    private $customers;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * State constructor.
     * @param ArrayCollection $customers
     */
    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return State
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     * @return State
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * @param ArrayCollection $customers
     * @return State
     */
    public function setCustomers(ArrayCollection $customers)
    {
        $this->customers = $customers;

        return $this;
    }
}
