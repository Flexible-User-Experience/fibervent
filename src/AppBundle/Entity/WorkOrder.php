<?php

namespace AppBundle\Entity;

use AppBundle\Enum\RepairAccessTypeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * WorkOrder.
 *
 * @category Entity
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkOrderRepository")
 * @UniqueEntity("projectNumber")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class WorkOrder extends AbstractBase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=45, unique=true)
     */
    private $projectNumber;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isFromAudit = false;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
     */
    private $customer;

    /**
     * @var Windfarm
     *
     * @ORM\ManyToOne(targetEntity="Windfarm")
     * @ORM\JoinColumn(name="windfarm_id", referencedColumnName="id", nullable=true)
     */
    private $windfarm;

    /**
     * @var Audit
     *
     * @ORM\ManyToOne(targetEntity="Audit")
     * @ORM\JoinColumn(name="audit_id", referencedColumnName="id", nullable=true)
     */
    private $audit;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $certifyingCompanyName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $certifyingCompanyContactPerson;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $certifyingCompanyPhone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $certifyingCompanyEmail;

    /**
     * @var array
     *
     * @ORM\Column(name="repair_access_types", type="json_array", nullable=true)
     */
    private $repairAccessTypes = [];

    /**
     * @var WorkOrderTask[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="WorkOrderTask", mappedBy="workOrder", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $workOrderTasks;

    /**
     * @var DeliveryNote[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DeliveryNote", mappedBy="workOrder", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $deliveryNotes;

    /**
     * Methods.
     */

    /**
     * @return string
     */
    public function getProjectNumber()
    {
        return $this->projectNumber;
    }

    /**
     * @param string $projectNumber
     *
     * @return WorkOrder
     */
    public function setProjectNumber(string $projectNumber): WorkOrder
    {
        $this->projectNumber = $projectNumber;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFromAudit(): bool
    {
        return $this->isFromAudit;
    }

    /**
     * @param bool $isFromAudit
     *
     * @return WorkOrder
     */
    public function setIsFromAudit(bool $isFromAudit): WorkOrder
    {
        $this->isFromAudit = $isFromAudit;

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     *
     * @return WorkOrder
     */
    public function setCustomer(Customer $customer): WorkOrder
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Windfarm
     */
    public function getWindfarm()
    {
        return $this->windfarm;
    }

    /**
     * @param Windfarm $windfarm
     *
     * @return WorkOrder
     */
    public function setWindfarm(Windfarm $windfarm): WorkOrder
    {
        $this->windfarm = $windfarm;

        return $this;
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
     * @return WorkOrder
     */
    public function setAudit(Audit $audit): WorkOrder
    {
        $this->audit = $audit;

        return $this;
    }

    /**
     * @return string
     */
    public function getCertifyingCompanyName()
    {
        return $this->certifyingCompanyName;
    }

    /**
     * @param string $certifyingCompanyName
     *
     * @return WorkOrder
     */
    public function setCertifyingCompanyName(string $certifyingCompanyName): WorkOrder
    {
        $this->certifyingCompanyName = $certifyingCompanyName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCertifyingCompanyContactPerson()
    {
        return $this->certifyingCompanyContactPerson;
    }

    /**
     * @param string $certifyingCompanyContactPerson
     *
     * @return WorkOrder
     */
    public function setCertifyingCompanyContactPerson(string $certifyingCompanyContactPerson): WorkOrder
    {
        $this->certifyingCompanyContactPerson = $certifyingCompanyContactPerson;

        return $this;
    }

    /**
     * @return string
     */
    public function getCertifyingCompanyPhone()
    {
        return $this->certifyingCompanyPhone;
    }

    /**
     * @param string $certifyingCompanyPhone
     *
     * @return WorkOrder
     */
    public function setCertifyingCompanyPhone(string $certifyingCompanyPhone): WorkOrder
    {
        $this->certifyingCompanyPhone = $certifyingCompanyPhone;

        return $this;
    }

    /**
     * @return string
     */
    public function getCertifyingCompanyEmail()
    {
        return $this->certifyingCompanyEmail;
    }

    /**
     * @param string $certifyingCompanyEmail
     *
     * @return WorkOrder
     */
    public function setCertifyingCompanyEmail(string $certifyingCompanyEmail): WorkOrder
    {
        $this->certifyingCompanyEmail = $certifyingCompanyEmail;

        return $this;
    }

    /**
     * @return array
     */
    public function getRepairAccessTypes()
    {
        return $this->repairAccessTypes;
    }

    /**
     * @param array $repairAccessTypes
     *
     * @return WorkOrder
     */
    public function setRepairAccessTypes(array $repairAccessTypes): WorkOrder
    {
        $this->repairAccessTypes = $repairAccessTypes;

        return $this;
    }

    /**
     * @param int $repairAccessType
     *
     * @return WorkOrder
     */
    public function addRepairAccessType(int $repairAccessType): WorkOrder
    {
        if (false === ($key = array_search($repairAccessType, $this->repairAccessTypes))) {
            $this->repairAccessTypes[] = $repairAccessType;
        }

        return $this;
    }

    /**
     * @param int $repairAccessType
     *
     * @return WorkOrder
     */
    public function removeRepairAccessType(int $repairAccessType): WorkOrder
    {
        if (false !== ($key = array_search($repairAccessType, $this->repairAccessTypes))) {
            unset($this->repairAccessTypes[$key]);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRepairAccessTypesString(): array
    {
        $repairAccessTypes = $this->getRepairAccessTypes();
        $repairAccessTypesString = [];
        foreach ($repairAccessTypes as $repairAccessType) {
            $repairAccessTypesString[] = RepairAccessTypeEnum::getDecodedStringFromType($repairAccessType);
        }

        return $repairAccessTypesString;
    }

    /**
     * @return WorkOrderTask[]|ArrayCollection
     */
    public function getWorkOrderTasks()
    {
        return $this->workOrderTasks;
    }

    /**
     * @param WorkOrderTask[]|ArrayCollection $workOrderTasks
     *
     * @return WorkOrder
     */
    public function setWorkOrderTasks($workOrderTasks)
    {
        $this->workOrderTasks = $workOrderTasks;

        return $this;
    }

    /**
     * @return DeliveryNote[]|ArrayCollection
     */
    public function getDeliveryNotes()
    {
        return $this->deliveryNotes;
    }

    /**
     * @param DeliveryNote[]|ArrayCollection $deliveryNotes
     *
     * @return WorkOrder
     */
    public function setDeliveryNotes($deliveryNotes)
    {
        $this->deliveryNotes = $deliveryNotes;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getProjectNumber() ? $this->getProjectNumber() : '';
    }
}
