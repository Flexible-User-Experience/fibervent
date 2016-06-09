<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Audit
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuditRepository")
 */
class Audit extends AbstractBase
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $beginDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $endDate;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=4000, nullable=true)
     */
    private $tools;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=4000, nullable=true)
     */
    private $observations;

    /**
     * @var Windmill
     *
     * @ORM\ManyToOne(targetEntity="Windmill", inversedBy="audits")
     */
    private $windmill;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AuditWindmillBlade", mappedBy="audit", cascade={"persist"})
     */
    private $auditWindmillBlades;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * Audit constructor.
     */
    public function __construct()
    {
        $this->auditWindmillBlades = new ArrayCollection();
    }

    /**
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * @param \DateTime $beginDate
     *
     * @return Audit
     */
    public function setBeginDate(\DateTime $beginDate)
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     *
     * @return Audit
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getTools()
    {
        return $this->tools;
    }

    /**
     * @param string $tools
     *
     * @return $this
     */
    public function setTools($tools)
    {
        $this->tools = $tools;

        return $this;
    }

    /**
     * @return string
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * @param string $observations
     *
     * @return $this
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * @return Windmill
     */
    public function getWindmill()
    {
        return $this->windmill;
    }

    /**
     * @param Windmill $windmill
     *
     * @return $this
     */
    public function setWindmill(Windmill $windmill)
    {
        $this->windmill = $windmill;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAuditWindmillBlades()
    {
        return $this->auditWindmillBlades;
    }

    /**
     * @param ArrayCollection $auditWindmillBlades
     *
     * @return $this
     */
    public function setAuditWindmillBlades(ArrayCollection $auditWindmillBlades)
    {
        $this->auditWindmillBlades = $auditWindmillBlades;

        return $this;
    }

    /**
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return $this
     */
    public function addAuditWindmillBlade(AuditWindmillBlade $auditWindmillBlade)
    {
        $auditWindmillBlade->setAudit($this);
        $this->auditWindmillBlades->add($auditWindmillBlade);

        return $this;
    }

    /**
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return $this
     */
    public function removeAuditWindmillBlade(AuditWindmillBlade $auditWindmillBlade)
    {
        $this->auditWindmillBlades->removeElement($auditWindmillBlade);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? '#' . $this->getId() . ' · ' . $this->getType() :  '---';
    }
}
