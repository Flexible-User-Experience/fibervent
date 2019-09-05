<?php

namespace AppBundle\Entity;

use AppBundle\Enum\RepairAccessTypeEnum;
use AppBundle\Enum\RepairWindmillSectionEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DeliveryNote.
 *
 * @category Entity
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeliveryNoteRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class DeliveryNote extends AbstractBase
{
    /**
     * @var WorkOrder
     *
     * @ORM\ManyToOne(targetEntity="WorkOrder")
     * @ORM\JoinColumn(name="work_order_id", referencedColumnName="id", nullable=false)
     */
    private $workOrder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var array
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $repairWindmillSections = [];

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="admin_user_id", referencedColumnName="id", nullable=false)
     */
    private $teamLeader;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="admin_user_id", referencedColumnName="id", nullable=true)
     */
    private $teamTechnician1;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="admin_user_id", referencedColumnName="id", nullable=true)
     */
    private $teamTechnician2;

    /**
     * @var Vehicle
     *
     * @ORM\ManyToOne(targetEntity="Vehicle")
     * @ORM\JoinColumn(name="vehicle_id", referencedColumnName="id", nullable=true)
     */
    private $vehicle;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $craneCompany;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $craneDriver;

    /**
     * @var array
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $accessTypes = [];

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $observations;

    /**
     * @var DeliveryNoteTimeRegister[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DeliveryNoteTimeRegister", mappedBy="DeliveryNote")
     */
    private $timeRegisters;

    /**
     * @var NonStandardUsedMaterial[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="NonStandardUsedMaterial", mappedBy="DeliveryNote")
     */
    private $nonStandardUsedMaterials;

    /**
     * @var WorkOrderTask[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="WorkOrderTask", mappedBy="DeliveryNote")
     */
    private $workOrderTasks;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalTripHours;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalWorkHours;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalStopHours;

    /**
     * Methods.
     */

    /**
     * @return WorkOrder
     */
    public function getWorkOrder(): WorkOrder
    {
        return $this->workOrder;
    }

    /**
     * @param WorkOrder $workOrder
     *
     * @return DeliveryNote
     */
    public function setWorkOrder(WorkOrder $workOrder): DeliveryNote
    {
        $this->workOrder = $workOrder;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     *
     * @return DeliveryNote
     */
    public function setDate(\DateTime $date): DeliveryNote
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return array
     */
    public function getRepairWindmillSections(): array
    {
        return $this->repairWindmillSections;
    }

    /**
     * @return string
     */
    public function getRepairWindmillSectionString(): string
    {
        $repairWindmillSections = $this->getRepairWindmillSections();
        $repairWindmillSectionsString = null;
        foreach ($repairWindmillSections as $repairWindmillSection) {
            if (!$repairWindmillSectionsString) {
                $repairWindmillSectionsString = RepairWindmillSectionEnum::getEnumArray()[$repairWindmillSection];
            } else {
                $repairWindmillSectionsString = $repairWindmillSectionsString.', '.RepairWindmillSectionEnum::getEnumArray()[$repairWindmillSection];
            }
        }

        return $repairWindmillSectionsString;
    }

    /**
     * @param array $repairWindmillSections
     *
     * @return DeliveryNote
     */
    public function setRepairWindmillSections(array $repairWindmillSections): DeliveryNote
    {
        $this->repairWindmillSections = $repairWindmillSections;

        return $this;
    }

    /**
     * @param int $repairWindmillSection
     *
     * @return DeliveryNote
     */
    public function addRepairWindmillSection(int $repairWindmillSection): DeliveryNote
    {
        if (false === ($key = array_search($repairWindmillSection, $this->repairWindmillSections))) {
            $this->repairWindmillSections[] = $repairWindmillSection;
        }

        return $this;
    }

    /**
     * @param int $repairWindmillSection
     *
     * @return DeliveryNote
     */
    public function removeRepairWindmillSection(int $repairWindmillSection): DeliveryNote
    {
        if (false !== ($key = array_search($repairWindmillSection, $this->repairWindmillSections))) {
            unset($this->repairWindmillSections[$key]);
        }

        return $this;
    }

    /**
     * @return User
     */
    public function getTeamLeader(): User
    {
        return $this->teamLeader;
    }

    /**
     * @param User $teamLeader
     *
     * @return DeliveryNote
     */
    public function setTeamLeader(User $teamLeader): DeliveryNote
    {
        $this->teamLeader = $teamLeader;

        return $this;
    }

    /**
     * @return User
     */
    public function getTeamTechnician1(): User
    {
        return $this->teamTechnician1;
    }

    /**
     * @param User $teamTechnician1
     *
     * @return DeliveryNote
     */
    public function setTeamTechnician1(User $teamTechnician1): DeliveryNote
    {
        $this->teamTechnician1 = $teamTechnician1;

        return $this;
    }

    /**
     * @return User
     */
    public function getTeamTechnician2(): User
    {
        return $this->teamTechnician2;
    }

    /**
     * @param User $teamTechnician2
     *
     * @return DeliveryNote
     */
    public function setTeamTechnician2(User $teamTechnician2): DeliveryNote
    {
        $this->teamTechnician2 = $teamTechnician2;

        return $this;
    }

    /**
     * @return Vehicle
     */
    public function getVehicle(): Vehicle
    {
        return $this->vehicle;
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return DeliveryNote
     */
    public function setVehicle(Vehicle $vehicle): DeliveryNote
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * @return string
     */
    public function getCraneCompany(): string
    {
        return $this->craneCompany;
    }

    /**
     * @param string $craneCompany
     *
     * @return DeliveryNote
     */
    public function setCraneCompany(string $craneCompany): DeliveryNote
    {
        $this->craneCompany = $craneCompany;

        return $this;
    }

    /**
     * @return string
     */
    public function getCraneDriver(): string
    {
        return $this->craneDriver;
    }

    /**
     * @param string $craneDriver
     *
     * @return DeliveryNote
     */
    public function setCraneDriver(string $craneDriver): DeliveryNote
    {
        $this->craneDriver = $craneDriver;

        return $this;
    }

    /**
     * @return array
     */
    public function getAccessTypes(): array
    {
        return $this->accessTypes;
    }

    /**
     * @param array $accessTypes
     *
     * @return DeliveryNote
     */
    public function setAccessTypes(array $accessTypes): DeliveryNote
    {
        $this->accessTypes = $accessTypes;

        return $this;
    }

    /**
     * @param int $accessType
     *
     * @return DeliveryNote
     */
    public function addAccessType(int $accessType): DeliveryNote
    {
        if (false === ($key = array_search($accessType, $this->accessTypes))) {
            $this->accessTypes[] = $accessType;
        }

        return $this;
    }

    /**
     * @param int $accessType
     *
     * @return DeliveryNote
     */
    public function removeAccessType(int $accessType): DeliveryNote
    {
        if (false !== ($key = array_search($accessType, $this->accessTypes))) {
            unset($this->accessTypes[$key]);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getAccessTypesString()
    {
        $accessTypes = $this->getAccessTypes();
        $accessTypesString = null;
        foreach ($accessTypes as $accessType) {
            if (!$accessTypesString) {
                $accessTypesString = RepairAccessTypeEnum::getEnumArray()[$accessType];
            } else {
                $accessTypesString = $accessTypesString.', '.RepairAccessTypeEnum::getEnumArray()[$accessType];
            }
        }

        return $accessTypesString;
    }

    /**
     * @return string
     */
    public function getObservations(): string
    {
        return $this->observations;
    }

    /**
     * @param string $observations
     *
     * @return DeliveryNote
     */
    public function setObservations(string $observations): DeliveryNote
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * @return DeliveryNoteTimeRegister[]|ArrayCollection
     */
    public function getTimeRegisters()
    {
        return $this->timeRegisters;
    }

    /**
     * @return NonStandardUsedMaterial[]|ArrayCollection
     */
    public function getNonStandardUsedMaterials()
    {
        return $this->nonStandardUsedMaterials;
    }

    /**
     * @return WorkOrderTask[]|ArrayCollection
     */
    public function getWorkOrderTasks()
    {
        return $this->workOrderTasks;
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
     * @return DeliveryNote
     */
    public function setTotalTripHours(float $totalTripHours): DeliveryNote
    {
        $this->totalTripHours = $totalTripHours;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalWorkHours(): float
    {
        return $this->totalWorkHours;
    }

    /**
     * @param float $totalWorkHours
     *
     * @return DeliveryNote
     */
    public function setTotalWorkHours(float $totalWorkHours): DeliveryNote
    {
        $this->totalWorkHours = $totalWorkHours;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalStopHours(): float
    {
        return $this->totalStopHours;
    }

    /**
     * @param float $totalStopHours
     *
     * @return DeliveryNote
     */
    public function setTotalStopHours(float $totalStopHours): DeliveryNote
    {
        $this->totalStopHours = $totalStopHours;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->workOrder.' / '.$this->date->format('d/m/Y');
    }
}
