<?php

namespace AppBundle\Enum;

/**
 * PresenceMonitoringCategoryEnum class.
 *
 * @category Enum
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 */
class PresenceMonitoringCategoryEnum
{
    const WORKDAY = 0;
    const DAYOFF = 1;
    const PERMITS = 2;
    const LEAVE = 3;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::WORKDAY => 'enum.repair_access_type.crane',
            self::DAYOFF => 'enum.repair_access_type.basket_crane',
            self::PERMITS => 'enum.repair_access_type.ropes',
            self::LEAVE => 'enum.repair_access_type.ground',
        );
    }

    /**
     * @param int $type
     *
     * @return string
     */
    public static function getDecodedStringFromType(int $type)
    {
        return self::getEnumArray()[$type];
    }
}
