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
     * @ORM\Column(type="float", precision=2)
     * @Assert\GreaterThan(value=1)
     */
    private $length;

    /**
     * Methods
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
    public function getQ0LengthString()
    {
        return number_format(0, 2, ',', '.') . 'm';
    }

    /**
     * @return string
     */
    public function getQ1LengthString()
    {
        return number_format($this->getQuarterLength(1), 2, ',', '.') . 'm';
    }

    /**
     * @return string
     */
    public function getQ2LengthString()
    {
        return number_format($this->getQuarterLength(2), 2, ',', '.') . 'm';
    }

    /**
     * @return string
     */
    public function getQ3LengthString()
    {
        return number_format($this->getQuarterLength(3), 2, ',', '.') . 'm';
    }

    /**
     * @return string
     */
    public function getQ4LengthString()
    {
        return number_format($this->getQuarterLength(4), 2, ',', '.') . 'm';
    }

    /**
     * @param int $quarter
     *
     * @return float
     */
    private function getQuarterLength($quarter)
    {
        return ($this->getLength() / 4) * $quarter;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getModel() ? $this->getModel() . ' (' . $this->getLength() . 'm)' : '---';
    }
}
