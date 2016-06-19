<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\ModelTrait;
use AppBundle\Entity\Traits\PowerTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Turbine
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TurbineRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class Turbine extends AbstractBase
{
    use ModelTrait;
    use PowerTrait;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $towerHeight;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $rotorDiameter;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Windmill", mappedBy="turbine")
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
     * Windmill constructor.
     */
    public function __construct()
    {
        $this->windmills = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getTowerHeight()
    {
        return $this->towerHeight;
    }

    /**
     * @param int $towerHeight
     *
     * @return Turbine
     */
    public function setTowerHeight($towerHeight)
    {
        $this->towerHeight = $towerHeight;

        return $this;
    }

    /**
     * @return int
     */
    public function getRotorDiameter()
    {
        return $this->rotorDiameter;
    }

    /**
     * @param int $rotorDiameter
     *
     * @return Turbine
     */
    public function setRotorDiameter($rotorDiameter)
    {
        $this->rotorDiameter = $rotorDiameter;

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
     * @return Turbine
     */
    public function setWindmills(ArrayCollection $windmills)
    {
        $this->windmills = $windmills;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getModel() ? $this->getModel() . ' (' . $this->getPower() . ')' : '---';
    }
}
