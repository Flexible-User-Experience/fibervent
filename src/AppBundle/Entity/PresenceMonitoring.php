<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PresenceMonitoring.
 *
 * @category Entity
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PresenceMonitoringRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class PresenceMonitoring extends AbstractBase
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="admin_user_id", referencedColumnName="id", nullable=false)
     */
    private $worker;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     */
    private $morningHourBegin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     */
    private $morningHourEnd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     */
    private $afternoonHourBegin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     */
    private $afternoonHourEnd;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalHours;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $normalHours;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $extraHours;

    /**
     * Methods.
     */

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     *
     * @return PresenceMonitoring
     */
    public function setDate(\DateTime $date): PresenceMonitoring
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return User
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * @param User $worker
     *
     * @return PresenceMonitoring
     */
    public function setWorker(User $worker): PresenceMonitoring
    {
        $this->worker = $worker;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getMorningHourBegin()
    {
        return $this->morningHourBegin;
    }

    /**
     * @param \DateTime $morningHourBegin
     *
     * @return PresenceMonitoring
     */
    public function setMorningHourBegin(\DateTime $morningHourBegin): PresenceMonitoring
    {
        $this->morningHourBegin = $morningHourBegin;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getMorningHourEnd()
    {
        return $this->morningHourEnd;
    }

    /**
     * @param \DateTime $morningHourEnd
     *
     * @return PresenceMonitoring
     */
    public function setMorningHourEnd(\DateTime $morningHourEnd): PresenceMonitoring
    {
        $this->morningHourEnd = $morningHourEnd;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAfternoonHourBegin()
    {
        return $this->afternoonHourBegin;
    }

    /**
     * @param \DateTime $afternoonHourBegin
     *
     * @return PresenceMonitoring
     */
    public function setAfternoonHourBegin(\DateTime $afternoonHourBegin): PresenceMonitoring
    {
        $this->afternoonHourBegin = $afternoonHourBegin;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAfternoonHourEnd()
    {
        return $this->afternoonHourEnd;
    }

    /**
     * @param \DateTime $afternoonHourEnd
     *
     * @return PresenceMonitoring
     */
    public function setAfternoonHourEnd(\DateTime $afternoonHourEnd): PresenceMonitoring
    {
        $this->afternoonHourEnd = $afternoonHourEnd;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalHours()
    {
        return $this->totalHours;
    }

    /**
     * @param float $totalHours
     *
     * @return PresenceMonitoring
     */
    public function setTotalHours(float $totalHours): PresenceMonitoring
    {
        $this->totalHours = $totalHours;

        return $this;
    }

    /**
     * @return float
     */
    public function getNormalHours()
    {
        return $this->normalHours;
    }

    /**
     * @param float $normalHours
     *
     * @return PresenceMonitoring
     */
    public function setNormalHours(float $normalHours): PresenceMonitoring
    {
        $this->normalHours = $normalHours;

        return $this;
    }

    /**
     * @return float
     */
    public function getExtraHours()
    {
        return $this->extraHours;
    }

    /**
     * @param float $extraHours
     *
     * @return PresenceMonitoring
     */
    public function setExtraHours(float $extraHours): PresenceMonitoring
    {
        $this->extraHours = $extraHours;

        return $this;
    }
}
