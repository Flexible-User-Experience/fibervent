<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Vehicle.
 *
 * @category Entity
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VehicleRepository")
 * @UniqueEntity("licensePlate")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class Vehicle extends AbstractBase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $licensePlate;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=true})
     */
    private $active;

    /**
     * Methods.
     */

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Vehicle
     */
    public function setName(string $name): Vehicle
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getLicensePlate()
    {
        return $this->licensePlate;
    }

    /**
     * @param string $licensePlate
     *
     * @return Vehicle
     */
    public function setLicensePlate(string $licensePlate): Vehicle
    {
        $this->licensePlate = $licensePlate;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return Vehicle
     */
    public function setActive(bool $active): Vehicle
    {
        $this->active = $active;

        return $this;
    }
}
