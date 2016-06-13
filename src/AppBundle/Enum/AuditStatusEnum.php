<?php

namespace AppBundle\Enum;

/**
 * AuditStatusEnum class
 *
 * @category Enum
 * @package  AppBundle\Enum
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class AuditStatusEnum
{
    const PENDING  = 0;
    const DOING    = 1;
    const DONE     = 2;
    const INVOICED = 3;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::PENDING  => 'Pendent',
            self::DOING    => 'En procés',
            self::DONE     => 'Fet',
            self::INVOICED => 'Facturat',
        );
    }
}
