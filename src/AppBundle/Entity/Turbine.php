<?php

namespace AppBundle\Entity;

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
 */
class Turbine extends AbstractBase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $model;

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
     * @var integer
     *
     * @ORM\Column(type="integer", nullable = true)
     */
    private $power;

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
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     *
     * @return Turbine
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
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
     * @return int
     */
    public function getPower()
    {
        return $this->power;
    }

    /**
     * @param int $power
     *
     * @return Turbine
     */
    public function setPower($power)
    {
        $this->power = $power;

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
}
