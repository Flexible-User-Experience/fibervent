<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\GpsCoordinatesTrait;
use AppBundle\Entity\Traits\ImageTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Photo.
 *
 * @category Entity
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PhotoRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 * @Vich\Uploadable
 */
class Photo extends AbstractBase
{
    use GpsCoordinatesTrait;
    use ImageTrait;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="image", fileNameProperty="imageName")
     * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/png", "image/gif"}
     * )
     * @Assert\Image(minWidth = 1200)
     */
    private $imageFile;

    /**
     * @var BladeDamage
     *
     * @ORM\ManyToOne(targetEntity="BladeDamage", inversedBy="photos")
     */
    private $bladeDamage;

    /**
     * Methods.
     */

    /**
     * @return BladeDamage
     */
    public function getBladeDamage()
    {
        return $this->bladeDamage;
    }

    /**
     * @param BladeDamage $bladeDamage
     *
     * @return $this
     */
    public function setBladeDamage(BladeDamage $bladeDamage)
    {
        $this->bladeDamage = $bladeDamage;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getImageName() ? $this->getImageName() : '---';
    }
}
