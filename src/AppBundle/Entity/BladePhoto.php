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
 * BladePhoto.
 *
 * @category Entity
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BladePhotoRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 * @Vich\Uploadable
 */
class BladePhoto extends AbstractBase
{
    use GpsCoordinatesTrait;
    use ImageTrait;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="blade", fileNameProperty="imageName")
     * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/png", "image/gif"}
     * )
     * @Assert\Image(minWidth = 1200)
     */
    private $imageFile;

    /**
     * @var AuditWindmillBlade
     *
     * @ORM\ManyToOne(targetEntity="AuditWindmillBlade", inversedBy="bladePhotos")
     */
    private $auditWindmillBlade;

    /**
     * Methods.
     */

    /**
     * @return AuditWindmillBlade
     */
    public function getAuditWindmillBlade()
    {
        return $this->auditWindmillBlade;
    }

    /**
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return BladePhoto
     */
    public function setAuditWindmillBlade(AuditWindmillBlade $auditWindmillBlade)
    {
        $this->auditWindmillBlade = $auditWindmillBlade;

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
