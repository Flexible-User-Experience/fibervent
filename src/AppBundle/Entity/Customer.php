<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CityTrait;
use AppBundle\Entity\Traits\CodeTrait;
use AppBundle\Entity\Traits\NameTrait;
use AppBundle\Entity\Traits\StateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

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
 */
class Customer extends AbstractBase
{
    use NameTrait;
    use StateTrait;
    use CityTrait;
    use CodeTrait;

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
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zip;

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
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return Customer
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     *
     * @return Customer
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

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
    public function setWindfarms(ArrayCollection $windfarms)
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
    public function setContacts(ArrayCollection $contacts)
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
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ? $this->getName() : '---';
    }
}
