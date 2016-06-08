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
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
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
     * @ORM\OneToMany(targetEntity="AuditWindmillBlade", mappedBy="audit")
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
    public function setBeginDate(Audit $beginDate)
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
    public function setEndDate(Audit $endDate)
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
     * @return Audit
     */
    public function setStatus(Audit $status)
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
     * @return Audit
     */
    public function setType(Audit $type)
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
     * @return Audit
     */
    public function setTools(Audit $tools)
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
     * @return Audit
     */
    public function setObservations(Audit $observations)
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
     * @return Audit
     */
    public function setWindmill(Audit $windmill)
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
     * @return Audit
     */
    public function setAuditWindmillBlades(ArrayCollection $auditWindmillBlades)
    {
        $this->auditWindmillBlades = $auditWindmillBlades;

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
