<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\ObservationsTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * AuditWindmillBlade
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuditWindmillBladeRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)           
 */
class AuditWindmillBlade extends AbstractBase
{
    use ObservationsTrait;

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
     * @ORM\OrderBy({"number"="ASC"})
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
     * @param BladeDamage $bladeDamage
     *
     * @return $this
     */
    public function addBladeDamage(BladeDamage $bladeDamage)
    {
        $bladeDamage->setAuditWindmillBlade($this);
        $this->bladeDamages->add($bladeDamage);

        return $this;
    }

    /**
     * @param BladeDamage $bladeDamage
     *
     * @return $this
     */
    public function removeBladeDamage(BladeDamage $bladeDamage)
    {
        $this->bladeDamages->removeElement($bladeDamage);

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
