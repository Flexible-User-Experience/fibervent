<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\ImageTrait;
use AppBundle\Entity\Traits\RemovedAtTrait;
use AppBundle\Enum\UserRolesEnum;
use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class User.
 *
 * @category Entity
 *
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="admin_user")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    use ImageTrait;
    use RemovedAtTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="user", fileNameProperty="imageName")
     * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/png", "image/gif"}
     * )
     * @Assert\Image(minWidth = 320)
     */
    private $imageFile;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="contacts")
     */
    private $customer;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private $language;

    /**
     * Methods.
     */

    /**
     * Get id.
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     *
     * @return User
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return int
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param int $language
     *
     * @return User
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function fullContactInfoString()
    {
        return $this->getLastname().', '.$this->getFirstname().' · '.$this->getEmail().($this->getPhone() ? ' · '.$this->getPhone() : '');
    }

    /**
     * @return string
     */
    public function contactInfoString()
    {
        return $this->getLastname().', '.$this->getFirstname();
    }

    /**
     * @return string
     */
    public function getBsStatusRole()
    {
        $status = 'primary';
        if ($this->hasRole(UserRolesEnum::ROLE_OPERATOR)) {
            $status = 'success';
        } elseif ($this->hasRole(UserRolesEnum::ROLE_TECHNICIAN)) {
            $status = 'info';
        } elseif ($this->hasRole(UserRolesEnum::ROLE_ADMIN)) {
            $status = 'warning';
        } elseif ($this->hasRole(UserRolesEnum::ROLE_SUPER_ADMIN)) {
            $status = 'danger';
        }

        return $status;
    }

    /**
     * @return string
     */
    public function getRoleString()
    {
        $role = UserRolesEnum::getEnumArray()[UserRolesEnum::ROLE_CUSTOMER];
        if ($this->hasRole(UserRolesEnum::ROLE_CUSTOMER)) {
            $role = UserRolesEnum::getEnumArray()[UserRolesEnum::ROLE_CUSTOMER];
        } elseif ($this->hasRole(UserRolesEnum::ROLE_OPERATOR)) {
            $role = UserRolesEnum::getEnumArray()[UserRolesEnum::ROLE_OPERATOR];
        } elseif ($this->hasRole(UserRolesEnum::ROLE_TECHNICIAN)) {
            $role = UserRolesEnum::getEnumArray()[UserRolesEnum::ROLE_TECHNICIAN];
        } elseif ($this->hasRole(UserRolesEnum::ROLE_ADMIN)) {
            $role = UserRolesEnum::getEnumArray()[UserRolesEnum::ROLE_ADMIN];
        } elseif ($this->hasRole(UserRolesEnum::ROLE_SUPER_ADMIN)) {
            $role = UserRolesEnum::getEnumArray()[UserRolesEnum::ROLE_SUPER_ADMIN];
        }

        return $role;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername() ? $this->getFullname() : '---';
    }
}
