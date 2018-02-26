<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * AuditWindmillBlade.
 *
 * @category Entity
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuditWindmillBladeRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
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
     * @ORM\OrderBy({"number"="ASC"})
     */
    private $bladeDamages;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Observation", mappedBy="auditWindmillBlade", cascade={"persist", "remove"}, orphanRemoval=true))
     * @ORM\OrderBy({"position"="ASC"})
     */
    private $observations;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BladePhoto", mappedBy="auditWindmillBlade", cascade={"persist", "remove"}, orphanRemoval=true))
     */
    private $bladePhotos;

    /**
     * Methods.
     */

    /**
     * AuditWindmillBlade constructor.
     */
    public function __construct()
    {
        $this->bladeDamages = new ArrayCollection();
        $this->observations = new ArrayCollection();
        $this->bladePhotos = new ArrayCollection();
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
     * @param array|ArrayCollection $bladeDamages
     *
     * @return AuditWindmillBlade
     */
    public function setBladeDamages($bladeDamages)
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
     * @return ArrayCollection
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * @param ArrayCollection $observations
     *
     * @return AuditWindmillBlade
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * @param Observation $observation
     *
     * @return $this
     */
    public function addObservation(Observation $observation)
    {
        $observation->setAuditWindmillBlade($this);
        $this->observations->add($observation);

        return $this;
    }

    /**
     * @param Observation $observation
     *
     * @return $this
     */
    public function removeOservation(Observation $observation)
    {
        $this->observations->removeElement($observation);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getBladePhotos()
    {
        return $this->bladePhotos;
    }

    /**
     * @param ArrayCollection $bladePhotos
     *
     * @return AuditWindmillBlade
     */
    public function setBladePhotos($bladePhotos)
    {
        $this->bladePhotos = $bladePhotos;

        return $this;
    }

    /**
     * @param BladePhoto $bladePhoto
     *
     * @return $this
     */
    public function addBladePhoto(BladePhoto $bladePhoto)
    {
        $bladePhoto->setAuditWindmillBlade($this);
        $this->bladePhotos->add($bladePhoto);

        return $this;
    }

    /**
     * @param BladePhoto $bladePhoto
     *
     * @return $this
     */
    public function removeBladePhoto(BladePhoto $bladePhoto)
    {
        $this->bladePhotos->removeElement($bladePhoto);

        return $this;
    }

    /**
     * @param BladeDamage $bladeDamage
     *
     * @return int|null
     */
    public function getCalculatedNumberByRadius(BladeDamage $bladeDamage)
    {
        $result = null;
        if (count($this->getBladeDamages()) > 0) {
            $result = 1;
            /** @var BladeDamage $item */
            foreach ($this->getBladeDamages() as $item) {
                if ($item->getId() == $bladeDamage->getId()) {
                    break;
                }
                ++$result;
            }
        } else {
            $result = 1;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getId() ? $this->getAudit().' · '.$this->getWindmillBlade()->getCode() : '---';
    }
}
