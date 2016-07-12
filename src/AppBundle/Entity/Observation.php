<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\DescriptionTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Observation
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ObservationRepository")
 */
class Observation extends AbstractBase
{
    use DescriptionTrait;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $damageNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var AuditWindmillBlade
     *
     * @ORM\ManyToOne(targetEntity="AuditWindmillBlade", inversedBy="observations")
     */
    private $auditWindmillBlade;

    /**
     *
     *
     * Methods
     *
     *
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
    public function setAuditWindmillBlade(AuditWindmillBlade $auditWindmillBlade)
    {
        $this->auditWindmillBlade = $auditWindmillBlade;

        return $this;
    }
}
