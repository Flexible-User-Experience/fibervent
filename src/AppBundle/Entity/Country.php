<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Intl\Intl;

/**
 * Country
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountryRepository")
 * @UniqueEntity("code")
 */
class Country extends AbstractBase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2, unique = true)
     */
    private $code;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ArrayCollection
     *
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
     */
    public function __construct()
    {
        $this->states = new ArrayCollection();
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
     *
     * @return Country
     */
    public function setStates(ArrayCollection $states)
    {
        $this->states = $states;

        return $this;
    }

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
     * @return Country
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return Intl::getRegionBundle()->getCountryName($this->getCode(), 'ES');
    }

    /**
     * Set Name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function __toString()
    {
        return $this->getCode() ? $this->getName() : '---';
    }
}
