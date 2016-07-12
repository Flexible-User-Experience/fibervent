<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CodeTrait;
use AppBundle\Entity\Traits\GpsCoordinatesTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Windmill
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="windfarm_code_unique", columns={"windfarm_id", "code"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WindmillRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class Windmill extends AbstractBase
{
    use GpsCoordinatesTrait;
    use CodeTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $code;

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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="WindmillBlade", mappedBy="windmill", cascade={"persist"})
     */
    private $windmillBlades;

    /**
     * @var Blade
     *
     * @ORM\ManyToOne(targetEntity="Blade")
     */
    private $bladeType;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Audit", mappedBy="windmill")
     */
    private $audits;

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
        $this->windmillBlades = new ArrayCollection();
        $this->audits = new ArrayCollection();
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

    /**
     * @return ArrayCollection
     */
    public function getWindmillBlades()
    {
        return $this->windmillBlades;
    }

    /**
     * @param ArrayCollection $windmillBlades
     *
     * @return Windmill
     */
    public function setWindmillBlades(ArrayCollection $windmillBlades)
    {
        $this->windmillBlades = $windmillBlades;

        return $this;
    }

    /**
     * @param WindmillBlade $windmillBlade
     *
     * @return $this
     */
    public function addWindmillBlade(WindmillBlade $windmillBlade)
    {
        $windmillBlade->setWindmill($this);
        $this->windmillBlades->add($windmillBlade);

        return $this;
    }

    /**
     * @param WindmillBlade $windmillBlade
     *
     * @return $this
     */
    public function removeWindmillBlade(WindmillBlade $windmillBlade)
    {
        $this->windmillBlades->removeElement($windmillBlade);

        return $this;
    }

    /**
     * @return Blade
     */
    public function getBladeType()
    {
        return $this->bladeType;
    }

    /**
     * @param Blade $bladeType
     *
     * @return Windmill
     */
    public function setBladeType(Blade $bladeType)
    {
        $this->bladeType = $bladeType;

        return $this;
    }

    /**
     * @param Audit $audit
     *
     * @return $this
     */
    public function addAudit(Audit $audit)
    {
        $audit->setWindmill($this);
        $this->audits->add($audit);

        return $this;
    }

    /**
     * @param Audit $audit
     *
     * @return $this
     */
    public function removeAudit(Audit $audit)
    {
        $this->audits->removeElement($audit);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAudits()
    {
        return $this->audits;
    }

    /**
     * @param ArrayCollection $audits
     *
     * @return Windmill
     */
    public function setAudits(ArrayCollection $audits)
    {
        $this->audits = $audits;

        return $this;
    }

    /**
     * @return string
     */
    public function getPdfModelString()
    {
        return 'Torre ' . $this->getTurbine()->getTowerHeight() . 'm / Rotor Ø' . $this->getTurbine()->getRotorDiameter() . 'm / Pala ' . $this->getBladeType()->getLength() . 'm';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCode() ? $this->getWindfarm()->getCustomer()->getName() . ' · ' . $this->getWindfarm()->getName() . ' · ' . $this->getCode()  : '---';
    }
}
