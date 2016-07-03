<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\ModelTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Blade
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BladeRepository")
 * @UniqueEntity("model")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class Blade extends AbstractBase
{
    use ModelTrait;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $length;

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
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     *
     * @return Blade
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getModel() ? $this->getModel() . ' (' . $this->getLength() . 'm)' : '---';
    }
}
