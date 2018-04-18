<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\ObservationsTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Observation.
 *
 * @category Entity
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ObservationRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class Observation extends AbstractBase
{
    use ObservationsTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default" = 1})
     */
    private $position = 1;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $damageNumber;

    /**
     * @var AuditWindmillBlade
     *
     * @ORM\ManyToOne(targetEntity="AuditWindmillBlade", inversedBy="observations")
     */
    private $auditWindmillBlade;

    /**
     * Methods.
     */

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     *
     * @return Observation
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return int
     */
    public function getDamageNumber()
    {
        return $this->damageNumber;
    }

    /**
     * @param int $damageNumber
     *
     * @return Observation
     */
    public function setDamageNumber($damageNumber)
    {
        $this->damageNumber = $damageNumber;

        return $this;
    }

    /**
     * @return AuditWindmillBlade
     */
    public function getAuditWindmillBlade()
    {
        return $this->auditWindmillBlade;
    }

    /**
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return Observation
     */
    public function setAuditWindmillBlade($auditWindmillBlade)
    {
        $this->auditWindmillBlade = $auditWindmillBlade;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getId() ? (string) $this->getId() : '---';
    }
}
