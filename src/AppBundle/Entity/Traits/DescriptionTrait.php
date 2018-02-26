<?php

namespace AppBundle\Entity\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description trait
 *
 * @category Trait
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
