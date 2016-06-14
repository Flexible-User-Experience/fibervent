<?php

namespace AppBundle\Enum;

/**
 * Class BladeDamagePositionEnum
 *
 * @category Enum
 * @package  AppBundle\Enum
 * @author   David Romaní <david@flux.cat>
 */
class BladeDamagePositionEnum
{
    const VALVE_PRESSURE = 0;
    const VALVE_SUCTION  = 1;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::VALVE_PRESSURE => 'Valva pressió',
            self::VALVE_SUCTION  => 'Valva succió',
        );
    }
}
