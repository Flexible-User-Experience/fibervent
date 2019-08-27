<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Damage.
 *
 * @category Entity
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkOrderRepository")
 * @UniqueEntity("projectNumber")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class WorkOrder extends AbstractBase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=45, unique=true)
     */
    protected $projectNumber;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isFromAudit;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer")
     */
    private $customer;

    /**
     * @var Windfarm
     *
     * @ORM\ManyToOne(targetEntity="Windfarm")
     */
    private $windfarm;

    /**
     * @var Audit
     *
     * @ORM\ManyToOne(targetEntity="Audit", nullable=true)
     * @ORM\JoinColumn(name="audit_id", referencedColumnName="id", nullable=true)
     */
    private $audit;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $certifyingCompanyName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $certifyingCompanyContactPerson;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $certifyingCompanyPhone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $certifyingCompanyEmail;

    /**
     * Methods.
     */

    /**
     * @return string
     */
    public function getProjectNumber(): string
    {
        return $this->projectNumber;
    }

    /**
     * @param string $projectNumber
     *
     * @return WorkOrder
     */
    public function setProjectNumber(string $projectNumber): WorkOrder
    {
        $this->projectNumber = $projectNumber;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFromAudit(): bool
    {
        return $this->isFromAudit;
    }

    /**
     * @param bool $isFromAudit
     *
     * @return WorkOrder
     */
    public function setIsFromAudit(bool $isFromAudit): WorkOrder
    {
        $this->isFromAudit = $isFromAudit;
        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     *
     * @return WorkOrder
     */
    public function setCustomer(Customer $customer): WorkOrder
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return Windfarm
     */
    public function getWindfarm(): Windfarm
    {
        return $this->windfarm;
    }

    /**
     * @param Windfarm $windfarm
     *
     * @return WorkOrder
     */
    public function setWindfarm(Windfarm $windfarm): WorkOrder
    {
        $this->windfarm = $windfarm;
        return $this;
    }

    /**
     * @return Audit
     */
    public function getAudit(): Audit
    {
        return $this->audit;
    }

    /**
     * @param Audit $audit
     *
     * @return WorkOrder
     */
    public function setAudit(Audit $audit): WorkOrder
    {
        $this->audit = $audit;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertifyingCompanyName(): string
    {
        return $this->certifyingCompanyName;
    }

    /**
     * @param string $certifyingCompanyName
     *
     * @return WorkOrder
     */
    public function setCertifyingCompanyName(string $certifyingCompanyName): WorkOrder
    {
        $this->certifyingCompanyName = $certifyingCompanyName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertifyingCompanyContactPerson(): string
    {
        return $this->certifyingCompanyContactPerson;
    }

    /**
     * @param string $certifyingCompanyContactPerson
     *
     * @return WorkOrder
     */
    public function setCertifyingCompanyContactPerson(string $certifyingCompanyContactPerson): WorkOrder
    {
        $this->certifyingCompanyContactPerson = $certifyingCompanyContactPerson;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertifyingCompanyPhone(): string
    {
        return $this->certifyingCompanyPhone;
    }

    /**
     * @param string $certifyingCompanyPhone
     *
     * @return WorkOrder
     */
    public function setCertifyingCompanyPhone(string $certifyingCompanyPhone): WorkOrder
    {
        $this->certifyingCompanyPhone = $certifyingCompanyPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertifyingCompanyEmail(): string
    {
        return $this->certifyingCompanyEmail;
    }

    /**
     * @param string $certifyingCompanyEmail
     *
     * @return WorkOrder
     */
    public function setCertifyingCompanyEmail(string $certifyingCompanyEmail): WorkOrder
    {
        $this->certifyingCompanyEmail = $certifyingCompanyEmail;
        return $this;
    }
}
