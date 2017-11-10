<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\AddresTrait;
use AppBundle\Entity\Traits\CityTrait;
use AppBundle\Entity\Traits\CodeTrait;
use AppBundle\Entity\Traits\ImageTrait;
use AppBundle\Entity\Traits\NameTrait;
use AppBundle\Entity\Traits\PostalCodeTrait;
use AppBundle\Entity\Traits\StateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Customer
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   Anton Serra <aserratorta@gmail.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 * @Vich\Uploadable
 */
class Customer extends AbstractBase
{
    use NameTrait;
    use AddresTrait;
    use PostalCodeTrait;
    use StateTrait;
    use CityTrait;
    use CodeTrait;
    use ImageTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email(strict=true, checkMX=true, checkHost=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(checkDNS=true)
     */
    private $web;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="customer", fileNameProperty="imageName")
     * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/png", "image/gif"}
     * )
     * @Assert\Image(minWidth = 250)
     */
    private $imageFile;

    /**
     * @var State
     *
     * @ORM\ManyToOne(targetEntity="State", inversedBy="customers")
     */
    private $state;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Windfarm", mappedBy="customer", cascade={"persist", "remove"})
     */
    private $windfarms;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="customer", cascade={"persist", "remove"})
     */
    private $contacts;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private $showLogoInPdfs = false;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        $this->windfarms = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return Customer
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * @param string $web
     *
     * @return Customer
     */
    public function setWeb($web)
    {
        $this->web = $web;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getWindfarms()
    {
        return $this->windfarms;
    }

    /**
     * @param ArrayCollection $windfarms
     *
     * @return Customer
     */
    public function setWindfarms($windfarms)
    {
        $this->windfarms = $windfarms;

        return $this;
    }

    /**
     * @param Windfarm $windfarm
     *
     * @return $this
     */
    public function addWindfarm(Windfarm $windfarm)
    {
        $windfarm->setCustomer($this);
        $this->windfarms->add($windfarm);

        return $this;
    }

    /**
     * @param Windfarm $windfarm
     *
     * @return $this
     */
    public function removeWindfarm(Windfarm $windfarm)
    {
        $windfarm->setCustomer(null);
        $this->windfarms->removeElement($windfarm);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param ArrayCollection $contacts
     *
     * @return Customer
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
        
        return $this;
    }

    /**
     * @param User $contact
     *
     * @return $this
     */
    public function addContact(User $contact)
    {
        $contact->setCustomer($this);
        $this->contacts->add($contact);

        return $this;
    }

    /**
     * @param User $contact
     *
     * @return $this
     */
    public function removeContact(User $contact)
    {
        $contact->setCustomer(null);
        $this->contacts->removeElement($contact);

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowLogoInPdfs()
    {
        return $this->showLogoInPdfs;
    }

    /**
     * @param bool $showLogoInPdfs
     *
     * @return $this
     */
    public function setShowLogoInPdfs(bool $showLogoInPdfs)
    {
        $this->showLogoInPdfs = $showLogoInPdfs;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ? $this->getName() : '---';
    }
}
