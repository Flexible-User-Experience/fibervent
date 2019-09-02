<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * workorderTask.
 *
 * @category Entity
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkOrderTaskRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class WorkOrderTask extends AbstractBase
{
    /**
     * @var WorkOrder
     *
     * @ORM\ManyToOne(targetEntity="WorkOrder")
     * @ORM\JoinColumn(name="work_order_id", referencedColumnName="id", nullable=false)
     */
    private $workOrder;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
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
     * @return WorkOrderTask
     */
    public function setWorkOrder(WorkOrder $workOrder): WorkOrderTask
    {
        $this->workOrder = $workOrder;

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
     * @return WorkOrderTask
     */
    public function setIsFromAudit(bool $isFromAudit): WorkOrderTask
    {
        $this->isFromAudit = $isFromAudit;

        return $this;
    }

    /**
     * @return BladeDamage
     */
    public function getBladeDamage(): BladeDamage
    {
        return $this->bladeDamage;
    }

    /**
     * @param BladeDamage $bladeDamage
     *
     * @return WorkOrderTask
     */
    public function setBladeDamage(BladeDamage $bladeDamage): WorkOrderTask
    {
        $this->bladeDamage = $bladeDamage;

        return $this;
    }

    /**
     * @return WindmillBlade
     */
    public function getWindmillBlade(): WindmillBlade
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
    public function getWindmill(): Windmill
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
    public function getPosition(): int
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
    public function getRadius(): int
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
    public function getDistance(): int
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
    public function getSize(): int
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
    public function getEdge(): int
    {
        return $this->edge;
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
    public function isCompleted(): bool
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return WorkOrderTask
     */
    public function setDescription(string $description): WorkOrderTask
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getWorkOrder().' '.$this->getId();
    }
}
