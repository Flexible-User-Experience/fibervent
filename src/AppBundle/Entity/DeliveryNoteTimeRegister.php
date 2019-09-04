<?php

namespace AppBundle\Entity;

use AppBundle\Enum\TimeRegisterShiftEnum;
use AppBundle\Enum\TimeRegisterTypeEnum;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DeliveryNoteTimeRegister.
 *
 * @category Entity
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeliveryNoteTimeRegisterRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class DeliveryNoteTimeRegister extends AbstractBase
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $shift;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time")
     */
    private $begin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time")
     */
    private $end;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalHours;

    /**
     * Methods.
     */

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTypeString()
    {
        return TimeRegisterTypeEnum::getStringValue($this);
    }

    /**
     * @param int $type
     *
     * @return DeliveryNoteTimeRegister
     */
    public function setType(int $type): DeliveryNoteTimeRegister
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getShift(): int
    {
        return $this->shift;
    }

    /**
     * @return string
     */
    public function getShiftString()
    {
        return TimeRegisterShiftEnum::getStringValue($this);
    }

    /**
     * @param int $shift
     *
     * @return DeliveryNoteTimeRegister
     */
    public function setShift(int $shift): DeliveryNoteTimeRegister
    {
        $this->shift = $shift;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBegin(): \DateTime
    {
        return $this->begin;
    }

    /**
     * @param \DateTime $begin
     *
     * @return DeliveryNoteTimeRegister
     */
    public function setBegin(\DateTime $begin): DeliveryNoteTimeRegister
    {
        $this->begin = $begin;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEnd(): \DateTime
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     *
     * @return DeliveryNoteTimeRegister
     */
    public function setEnd(\DateTime $end): DeliveryNoteTimeRegister
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalHours(): float
    {
        return $this->totalHours;
    }
}
