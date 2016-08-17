<?php

namespace AppBundle\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * Class DamageCategoryTranslation
 *
 * @category Translation
 * @package  AppBundle\Entity\Translations
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity
 * @ORM\Table(name="damage_category_translation",
 *   uniqueConstraints={@ORM\UniqueConstraint(name="lookup_damage_category_unique_idx", columns={
 *     "locale", "object_id", "field"
 *   })}
 * )
 */
class DamageCategoryTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DamageCategory", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
