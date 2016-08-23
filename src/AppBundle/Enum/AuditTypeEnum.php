<?php

namespace AppBundle\Enum;

use AppBundle\Entity\Audit;

/**
 * Class AuditTypeEnum
 *
 * @category Enum
 * @package  AppBundle\Enum
 * @author   David Romaní <david@flux.cat>
 */
class AuditTypeEnum
{
    const GROUND = 0;
    const ROPE   = 1;
    const INSIDE = 2;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::GROUND => 'terra (telescopi Fibervent)',
            self::ROPE   => 'cordes',
            self::INSIDE => 'interior pala',
        );
    }

    /**
     * @return array
     */
    public static function getLocalizedEnumArray()
    {
        return array(
            self::GROUND => 'enum.audit_type.ground',
            self::ROPE   => 'enum.audit_type.rope',
            self::INSIDE => 'enum.audit_type.inside',
        );
    }

    /**
     * @param Audit $audit
     *
     * @return string
     */
    public static function getStringValue(Audit $audit)
    {
        return self::getEnumArray()[$audit->getType()];
    }

    /**
     * @param Audit $audit
     *
     * @return string
     */
    public static function getStringLocalizedValue(Audit $audit)
    {
        return self::getLocalizedEnumArray()[$audit->getType()];
    }
}
