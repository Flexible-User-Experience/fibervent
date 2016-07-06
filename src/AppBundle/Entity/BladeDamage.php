<?php

namespace AppBundle\Entity;

use AppBundle\Enum\BladeDamagePositionEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @ORM\Column(type="integer")
     */
    protected $number;

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
     *
     *
     * Methods
     *
     *
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
    public function setPhotos(ArrayCollection $photos)
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
     * @param int $gap
     *
     * @return float
     */
    public function getDeltaGap($gap)
    {
        return ($this->getRadius() * $gap) / $this->getAuditWindmillBlade()->getWindmillBlade()->getWindmill()->getBladeType()->getLength();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getDamage()->getCode() ? $this->getDamage()->getCode() : '---';
    }
}
