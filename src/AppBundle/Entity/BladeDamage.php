<?php

namespace AppBundle\Entity;

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
     * @ORM\Column(type="integer")
     */
    protected $status;

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
     * @ORM\ManyToOne(targetEntity="AuditWindmillBlade")
     */
    private $auditWindmillBlade;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="BladeDamage")
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
    public function setDamage($damage)
    {
        $this->damage = $damage;

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
    public function setDamageCategory($damageCategory)
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
    public function setAuditWindmillBlade($auditWindmillBlade)
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
     * @return string
     */
    public function __toString()
    {
        return $this->getPosition() ? $this->getPosition() : '---';
    }
}
