<?php

namespace AppBundle\Entity;

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
     *
     *
     * Methods
     *
     *
     */

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
     * @return string
     */
    public function __toString()
    {
        return $this->getId() ? $this->getId() : '---';
    }
}
