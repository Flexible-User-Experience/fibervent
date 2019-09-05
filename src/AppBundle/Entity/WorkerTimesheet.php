<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * WorkerTimesheet.
 *
 * @category Entity
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkerTimesheetRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class WorkerTimesheet extends AbstractBase
{
    /**
     * @var DeliveryNote
     *
     * @ORM\ManyToOne(targetEntity="DeliveryNote")
     * @ORM\JoinColumn(name="delivery_note_id", referencedColumnName="id", nullable=false)
     */
    private $deliveryNote;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="admin_user_id", referencedColumnName="id", nullable=false)
     */
    private $worker;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $workDescription;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalNormalHours;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalVerticalHours;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalInclementWeatherHours;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalTripHours;

    /**
     * Methods.
     */

    /**
     * @return DeliveryNote
     */
    public function getDeliveryNote(): DeliveryNote
    {
        return $this->deliveryNote;
    }

    /**
     * @param DeliveryNote $deliveryNote
     *
     * @return WorkerTimesheet
     */
    public function setDeliveryNote(DeliveryNote $deliveryNote): WorkerTimesheet
    {
        $this->deliveryNote = $deliveryNote;

        return $this;
    }

    /**
     * @return User
     */
    public function getWorker(): User
    {
        return $this->worker;
    }

    /**
     * @param User $worker
     *
     * @return WorkerTimesheet
     */
    public function setWorker(User $worker): WorkerTimesheet
    {
        $this->worker = $worker;

        return $this;
    }

    /**
     * @return string
     */
    public function getWorkDescription(): string
    {
        return $this->workDescription;
    }

    /**
     * @param string $workDescription
     *
     * @return WorkerTimesheet
     */
    public function setWorkDescription(string $workDescription): WorkerTimesheet
    {
        $this->workDescription = $workDescription;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalNormalHours(): float
    {
        return $this->totalNormalHours;
    }

    /**
     * @param float $totalNormalHours
     *
     * @return WorkerTimesheet
     */
    public function setTotalNormalHours(float $totalNormalHours): WorkerTimesheet
    {
        $this->totalNormalHours = $totalNormalHours;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalVerticalHours(): float
    {
        return $this->totalVerticalHours;
    }

    /**
     * @param float $totalVerticalHours
     *
     * @return WorkerTimesheet
     */
    public function setTotalVerticalHours(float $totalVerticalHours): WorkerTimesheet
    {
        $this->totalVerticalHours = $totalVerticalHours;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalInclementWeatherHours(): float
    {
        return $this->totalInclementWeatherHours;
    }

    /**
     * @param float $totalInclementWeatherHours
     *
     * @return WorkerTimesheet
     */
    public function setTotalInclementWeatherHours(float $totalInclementWeatherHours): WorkerTimesheet
    {
        $this->totalInclementWeatherHours = $totalInclementWeatherHours;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalTripHours(): float
    {
        return $this->totalTripHours;
    }

    /**
     * @param float $totalTripHours
     *
     * @return WorkerTimesheet
     */
    public function setTotalTripHours(float $totalTripHours): WorkerTimesheet
    {
        $this->totalTripHours = $totalTripHours;

        return $this;
    }
}
