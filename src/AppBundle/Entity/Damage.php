<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CodeTrait;
use AppBundle\Entity\Traits\DescriptionTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Damage
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DamageRepository")
 * @UniqueEntity("code")
 */
class Damage extends AbstractBase
{
    use CodeTrait;
    use DescriptionTrait;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $section;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=45, unique=true)
     */
    private $code;

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
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param int $section
     *
     * @return Damage
     */
    public function setSection($section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Damage
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCode() ? $this->getCode() . ' · ' . $this->getDescription() : '---';
    }
}
