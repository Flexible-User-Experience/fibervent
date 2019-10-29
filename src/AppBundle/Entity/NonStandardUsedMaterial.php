<?php

namespace AppBundle\Entity;

use AppBundle\Enum\NonStandardUsedMaterialItemEnum;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * NonStandardUsedMaterial.
 *
 * @category Entity
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NonStandardUsedMaterialRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
class NonStandardUsedMaterial extends AbstractBase
{
    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $quantity;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $item;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $description;

    /**
     * @var DeliveryNote
     *
     * @ORM\ManyToOne(targetEntity="DeliveryNote", inversedBy="nonStandardUsedMaterials", cascade={"persist"})
     */
    private $deliveryNote;

    /**
     * Methods.
     */

    /**
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     *
     * @return NonStandardUsedMaterial
     */
    public function setQuantity(float $quantity): NonStandardUsedMaterial
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return int
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @return string
     */
    public function getItemString()
    {
        return NonStandardUsedMaterialItemEnum::getStringValue($this);
    }

    /**
     * @param int $item
     *
     * @return NonStandardUsedMaterial
     */
    public function setItem(int $item): NonStandardUsedMaterial
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return NonStandardUsedMaterial
     */
    public function setDescription(?string $description): NonStandardUsedMaterial
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return DeliveryNote
     */
    public function getDeliveryNote()
    {
        return $this->deliveryNote;
    }

    /**
     * @param DeliveryNote|null $deliveryNote
     *
     * @return NonStandardUsedMaterial
     */
    public function setDeliveryNote(?DeliveryNote $deliveryNote): NonStandardUsedMaterial
    {
        $this->deliveryNote = $deliveryNote;

        return $this;
    }
}
