<?php

namespace AppBundle\Entity\Traits;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Description trait
 *
 * @category Trait
 * @package  AppBundle\Entity\Traits
 * @author   Anton Serra <aserratorta@gmail.com>
 */
Trait DescriptionTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     */
    private $description;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
