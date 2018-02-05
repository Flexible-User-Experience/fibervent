<?php

namespace AppBundle\Entity;

use AppBundle\Enum\BladeDamageEdgeEnum;
use AppBundle\Enum\BladeDamagePositionEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BladeDamage
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BladeDamageRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class BladeDamage extends AbstractBase
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $position;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(value=0)
     */
    protected $radius;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default"=0})
     */
    protected $edge;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $distance;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $size;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default"=0})
     */
    protected $status = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default"=1})
     */
    protected $number = 1;

    /**
     * @var Damage
     *
     * @ORM\ManyToOne(targetEntity="Damage")
     */
    private $damage;

    /**
     * @var DamageCategory
     *
     * @ORM\ManyToOne(targetEntity="DamageCategory")
     */
    private $damageCategory;

    /**
     * @var AuditWindmillBlade
     *
     * @ORM\ManyToOne(targetEntity="AuditWindmillBlade", inversedBy="bladeDamages")
     */
    private $auditWindmillBlade;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="bladeDamage", cascade={"persist", "remove"}, orphanRemoval=true))
     */
    private $photos;

    /**
     * @var integer
     */
    private $calculatedNumberByRadius;

    /**
     * Methods
     */

    /**
     * BladeDamage constructor.
     */
    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getPositionString()
    {
        return BladeDamagePositionEnum::getStringValue($this);
    }

    /**
     * @return string
     */
    public function getPositionStringLocalized()
    {
        return BladeDamagePositionEnum::getStringLocalizedValue($this);
    }

    /**
     * @param int $position
     *
     * @return BladeDamage
     */
    public function setPosition($position)
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
     * @return string
     */
    public function getRadiusString()
    {
        return 'R'.$this->radius.'m';
    }

    /**
     * @param int $radius
     *
     * @return BladeDamage
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * Get Edge
     *
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
        return BladeDamageEdgeEnum::getStringValue($this);
    }

    /**
     * Set Edge
     *
     * @param int $edge
     *
     * @return $this
     */
    public function setEdge($edge)
    {
        $this->edge = $edge;

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
     * @return int
     */
    public function getDistanceScaled()
    {
        return $this->distance > 999 ? number_format(($this->distance / 1000), 1, ',', '.') : $this->distance;
    }

    /**
     * @return int
     */
    public function getDistanceString()
    {
        $dist = $this->distance . 'cm';
        if ($this->distance > 999) {
            $dist = number_format(($this->distance / 1000), 1, ',', '.') . 'm';
        }

        return $this->position == BladeDamagePositionEnum::EDGE_IN || $this->position == BladeDamagePositionEnum::EDGE_OUT ? '-' : $dist . ' ' . $this->getEdgeString();
    }

    /**
     * @return int
     */
    public function getLocalizedDistanceString()
    {
        $modifier = $this->distance > 999 ? 'm' : 'cm';

        return $this->position == BladeDamagePositionEnum::EDGE_IN || $this->position == BladeDamagePositionEnum::EDGE_OUT ? 'pdf.damage_table_body.none' : 'pdf.damage_table_body.' . $this->getEdgeString() . '_dist_' . $modifier;
    }

    /**
     * @param int $distance
     *
     * @return BladeDamage
     */
    public function setDistance($distance)
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
     * @return BladeDamage
     */
    public function setSize($size)
    {
        $this->size = $size;

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
     * @return BladeDamage
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Damage
     */
    public function getDamage()
    {
        return $this->damage;
    }

    /**
     * @param Damage $damage
     *
     * @return BladeDamage
     */
    public function setDamage(Damage $damage)
    {
        $this->damage = $damage;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     *
     * @return BladeDamage
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return DamageCategory
     */
    public function getDamageCategory()
    {
        return $this->damageCategory;
    }

    /**
     * @param DamageCategory $damageCategory
     *
     * @return BladeDamage
     */
    public function setDamageCategory(DamageCategory $damageCategory)
    {
        $this->damageCategory = $damageCategory;

        return $this;
    }

    /**
     * @return AuditWindmillBlade
     */
    public function getAuditWindmillBlade()
    {
        return $this->auditWindmillBlade;
    }

    /**
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return BladeDamage
     */
    public function setAuditWindmillBlade(AuditWindmillBlade $auditWindmillBlade)
    {
        $this->auditWindmillBlade = $auditWindmillBlade;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param ArrayCollection $photos
     *
     * @return BladeDamage
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * @param Photo $photo
     *
     * @return $this
     */
    public function addPhoto(Photo $photo)
    {
        $photo->setBladeDamage($this);
        $this->photos->add($photo);

        return $this;
    }

    /**
     * @param Photo $photo
     *
     * @return $this
     */
    public function removePhoto(Photo $photo)
    {
        $this->photos->removeElement($photo);

        return $this;
    }

    /**
     * @return int
     */
    public function getCalculatedNumberByRadius()
    {
        return $this->getAuditWindmillBlade()->getCalculatedNumberByRadius($this);
    }

    /**
     * @param int $calculatedNumberByRadius
     *
     * @return $this
     */
    public function setCalculatedNumberByRadius($calculatedNumberByRadius)
    {
        $this->calculatedNumberByRadius = $calculatedNumberByRadius;

        return $this;
    }

    /**
     * @return float
     */
    public function getDeltaGapVertical()
    {
        $gap = 0; // 5 - 24 - 43,5 - 62,5
        if ($this->getEdge() == BladeDamageEdgeEnum::EDGE_IN) {
            if ($this->getPosition() == BladeDamagePositionEnum::VALVE_PRESSURE) {
                $gap = 24;
            } elseif ($this->getPosition() == BladeDamagePositionEnum::VALVE_SUCTION) {
                $gap = 43.5;
            }
        } elseif ($this->getEdge() == BladeDamageEdgeEnum::EDGE_OUT) {
            if ($this->getPosition() == BladeDamagePositionEnum::VALVE_PRESSURE) {
                $gap = 5;
            } elseif ($this->getPosition() == BladeDamagePositionEnum::VALVE_SUCTION) {
                $gap = 62.5;
            }
        } elseif ($this->getEdge() == BladeDamageEdgeEnum::EDGE_UNDEFINED) {
            if ($this->getPosition() == BladeDamagePositionEnum::EDGE_IN) {
                $gap = 24;
            } elseif ($this->getPosition() == BladeDamagePositionEnum::EDGE_OUT) {
                $gap = 43.5;
            }
        }

        return $gap;
    }

    /**
     * @param string $indexLetter
     *
     * @return string
     */
    public function getGeneralSummaryDamageRowtoString($indexLetter)
    {
        return $indexLetter.')'.chr(10);
//        return $indexLetter.') '.$this->getEdgeString().' '.$this->getDamage()->getCode().' '.$this->getRadiusString().chr(10);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getDamage() && $this->getDamage()->getCode() ? $this->getDamage()->getCode() : '---';
    }
}
