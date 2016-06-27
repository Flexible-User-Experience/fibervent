<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\DescriptionTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DamageCategory
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DamageCategoryRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class DamageCategory extends AbstractBase
{
    use DescriptionTrait;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $category;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $priority;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $recommendedAction;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $colour;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * @return int
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param int $category
     *
     * @return DamageCategory
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param string $priority
     *
     * @return DamageCategory
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecommendedAction()
    {
        return $this->recommendedAction;
    }

    /**
     * @param string $recommendedAction
     *
     * @return DamageCategory
     */
    public function setRecommendedAction($recommendedAction)
    {
        $this->recommendedAction = $recommendedAction;

        return $this;
    }

    /**
     * @return string
     */
    public function getColour()
    {
        return $this->colour;
    }

    /**
     * @param string $colour
     *
     * @return DamageCategory
     */
    public function setColour($colour)
    {
        $this->colour = $colour;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCategory() ? (string) $this->getCategory() : '---';
    }
}
