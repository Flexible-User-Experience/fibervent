<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\PowerTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Turbine
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="model_unique", columns={"model", "power", "tower_height"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TurbineRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 * @UniqueEntity({"model", "power", "towerHeight"})
 */
class Turbine extends AbstractBase
{
    use PowerTrait;

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
     * @ORM\Column(type="float", precision=2)
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
     * @return ArrayCollection
     */
    public function getWindmills()
    {
        return $this->windmills;
    }

    /**
     * @param ArrayCollection|array $windmills
     *
     * @return Turbine
     */
    public function setWindmills($windmills)
    {
        $this->windmills = $windmills;

        return $this;
    }

    /**
     * @return string
     */
    public function pdfToString()
    {
        return $this->getModel() ? $this->getModel() . ' (' . $this->getPower() . 'MW)' : '---';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getModel() ? $this->getModel() . ' (' . $this->getPower() . 'MW) ' . $this->getTowerHeight() . 'm' : '---';
    }
}
