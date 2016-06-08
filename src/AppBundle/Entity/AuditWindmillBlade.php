<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AuditWindmillBlade
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuditWindmillBladeRepository")
 */
class AuditWindmillBlade extends AbstractBase
{

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique = true)
     */
    private $code;

    /**
     * @var Windmill
     *
     * @ORM\ManyToOne(targetEntity="Windmill", inversedBy="windmillBlades")
     */
    private $windmill;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * @return Windmill
     */
    public function getWindmill()
    {
        return $this->windmill;
    }

    /**
     * @param Windmill $windmill
     *
     * @return WindmillBlade
     */
    public function setWindmill(Windmill $windmill)
    {
        $this->windmill = $windmill;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCode() ? $this->getCode() : '---';
    }
}
