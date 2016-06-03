<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Country
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountryRepository")
 */
class Country extends AbstractBase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="State", mappedBy="country")
     */
    private $states;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * Country constructor.
     * @param ArrayCollection $states
     */
    public function __construct()
    {
        $this->states = new ArrayCollection();
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
     * @return Country
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * @param ArrayCollection $states
     * @return Country
     */
    public function setStates(ArrayCollection $states)
    {
        $this->states = $states;

        return $this;
    }
}
