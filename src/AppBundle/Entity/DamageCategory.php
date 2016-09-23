<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\DescriptionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

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
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\DamageCategoryTranslation")
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
     * @Gedmo\Translatable
     */
    private $priority;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     */
    private $recommendedAction;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $colour;

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Translations\DamageCategoryTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     * @Assert\Valid(deep = true)
     * @var ArrayCollection
     */
    private $translations;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

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
     * @return string
     */
    public function getColourWithoutPad()
    {
        return strtoupper(substr($this->colour, -6));
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
     * Add translation
     *
     * @param Translations\DamageCategoryTranslation $translation
     *
     * @return $this
     */
    public function addTranslation(Translations\DamageCategoryTranslation $translation)
    {
        if ($translation->getContent()) {
            $translation->setObject($this);
            $this->translations[] = $translation;
        }

        return $this;
    }

    /**
     * Remove translation
     *
     * @param Translations\DamageCategoryTranslation $translation
     *
     * @return $this
     */
    public function removeTranslation(Translations\DamageCategoryTranslation $translation)
    {
        $this->translations->removeElement($translation);

        return $this;
    }

    /**
     * Set translations
     *
     * @param ArrayCollection $translations
     *
     * @return $this
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;

        return $this;
    }

    /**
     * Get translations
     *
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCategory() ? (string) $this->getCategory() : '---';
    }
}
