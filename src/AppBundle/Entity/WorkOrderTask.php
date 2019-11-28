<?php

namespace AppBundle\Entity;

use AppBundle\Enum\BladeDamageEdgeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * WorkOrderTask.
 *
 * @category Entity
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkOrderTaskRepository")
 * @UniqueEntity("id")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class WorkOrderTask extends AbstractBase
{
    /**
     * @var WorkOrder
     *
     * @ORM\ManyToOne(targetEntity="WorkOrder", inversedBy="workOrderTasks", cascade={"persist"})
     */
    private $workOrder;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default"=0})
     */
    private $isFromAudit;

    /**
     * @var BladeDamage
     *
     * @ORM\OneToOne(targetEntity="BladeDamage")
     * @ORM\JoinColumn(name="blade_damage_id", referencedColumnName="id", nullable=true)
     */
    private $bladeDamage;

    /**
     * @var WindmillBlade
     *
     * @ORM\ManyToOne(targetEntity="WindmillBlade")
     * @ORM\JoinColumn(name="windmill_blade_id", referencedColumnName="id", nullable=true)
     */
    private $windmillBlade;

    /**
     * @var Windmill
     *
     * @ORM\ManyToOne(targetEntity="Windmill")
     * @ORM\JoinColumn(name="windmill_id", referencedColumnName="id", nullable=true)
     */
    private $windmill;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $radius;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $distance;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $size;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $edge;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private $isCompleted;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150)
     */
    private $description;

    /**
     * @var DeliveryNote[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="DeliveryNote", inversedBy="workOrderTasks")
     */
    private $deliveryNotes;

    /**
     * Methods.
     */

    /**
     * @return WorkOrder
     */
    public function getWorkOrder()
    {
        return $this->workOrder;
    }

    /**
     * @param WorkOrder|null $workOrder
     *
     * @return WorkOrderTask
     */
    public function setWorkOrder(?WorkOrder $workOrder): WorkOrderTask
    {
        $this->workOrder = $workOrder;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFromAudit()
    {
        return $this->isFromAudit;
    }

    /**
     * @param bool|null $isFromAudit
     *
     * @return WorkOrderTask
     */
    public function setIsFromAudit(?bool $isFromAudit): WorkOrderTask
    {
        $this->isFromAudit = $isFromAudit;

        return $this;
    }

    /**
     * @return BladeDamage
     */
    public function getBladeDamage()
    {
        return $this->bladeDamage;
    }

    /**
     * @param BladeDamage|null $bladeDamage
     *
     * @return WorkOrderTask
     */
    public function setBladeDamage(?BladeDamage $bladeDamage): WorkOrderTask
    {
        $this->bladeDamage = $bladeDamage;

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
     * @return WorkOrderTask
     */
    public function setWindmillBlade(WindmillBlade $windmillBlade): WorkOrderTask
    {
        $this->windmillBlade = $windmillBlade;

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
     * @return WorkOrderTask
     */
    public function setWindmill(Windmill $windmill): WorkOrderTask
    {
        $this->windmill = $windmill;

        return $this;
    }

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
     * @return WorkOrderTask
     */
    public function setPosition(int $position): WorkOrderTask
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return int
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * @param int $radius
     *
     * @return WorkOrderTask
     */
    public function setRadius(int $radius): WorkOrderTask
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param int $distance
     *
     * @return WorkOrderTask
     */
    public function setDistance(int $distance): WorkOrderTask
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     *
     * @return WorkOrderTask
     */
    public function setSize(int $size): WorkOrderTask
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return int
     */
    public function getEdge()
    {
        return $this->edge;
    }

    /**
     * @return string
     */
    public function getEdgeString()
    {
        return BladeDamageEdgeEnum::getStringValue($this->getBladeDamage());
    }

    /**
     * @param int $edge
     *
     * @return WorkOrderTask
     */
    public function setEdge(int $edge): WorkOrderTask
    {
        $this->edge = $edge;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCompleted()
    {
        return $this->isCompleted;
    }

    /**
     * @param bool $isCompleted
     *
     * @return WorkOrderTask
     */
    public function setIsCompleted(bool $isCompleted): WorkOrderTask
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return WorkOrderTask
     */
    public function setDescription(?string $description): WorkOrderTask
    {
        $this->description = $description;

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
     * @param DeliveryNote $deliveryNote
     *
     * @return $this
     */
    public function addDeliveryNote(DeliveryNote $deliveryNote)
    {
        $deliveryNote->addWorkOrderTask($this);
        $this->deliveryNotes->add($deliveryNote);

        return $this;
    }

    /**
     * @param DeliveryNote $deliveryNote
     *
     * @return $this
     */
    public function removeDeliveryNote(DeliveryNote $deliveryNote)
    {
        $deliveryNote->removeWorkOrderTask($this);
        $this->deliveryNotes->removeElement($deliveryNote);

        return $this;
    }

    /**
     * @param DeliveryNote[]|ArrayCollection $deliveryNotes
     *
     * @return WorkOrderTask
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
        return $this->getWorkOrder().' '.$this->getDescription();
    }
}
