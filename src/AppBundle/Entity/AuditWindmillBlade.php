<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AuditWindmillBlade
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuditWindmillBladeRepository")
 */
class AuditWindmillBlade extends AbstractBase
{
    /**
     * @var Audit
     *
     * @ORM\ManyToOne(targetEntity="Audit", inversedBy="auditWindmillBlades")
     */
    private $audit;

    /**
     * @var WindmillBlade
     *
     * @ORM\ManyToOne(targetEntity="WindmillBlade")
     */
    private $windmillBlade;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BladeDamage", mappedBy="auditWindmillBlade", cascade={"persist", "remove"}, orphanRemoval=true))
     */
    private $bladeDamages;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * AuditWindmillBlade constructor.
     */
    public function __construct()
    {
        $this->bladeDamages = new ArrayCollection();
    }

    /**
     * @return Audit
     */
    public function getAudit()
    {
        return $this->audit;
    }

    /**
     * @param Audit $audit
     *
     * @return AuditWindmillBlade
     */
    public function setAudit($audit)
    {
        $this->audit = $audit;

        return $this;
    }

    /**
     * @return WindmillBlade
     */
    public function getWindmillBlade()
    {
        return $this->windmillBlade;
    }

    /**
     * @param WindmillBlade $windmillBlade
     *
     * @return AuditWindmillBlade
     */
    public function setWindmillBlade($windmillBlade)
    {
        $this->windmillBlade = $windmillBlade;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getBladeDamages()
    {
        return $this->bladeDamages;
    }

    /**
     * @param ArrayCollection $bladeDamages
     *
     * @return AuditWindmillBlade
     */
    public function setBladeDamages(ArrayCollection $bladeDamages)
    {
        $this->bladeDamages = $bladeDamages;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getId() ? $this->getAudit() . ' · ' . $this->getWindmillBlade()->getCode() : '---';
    }
}
