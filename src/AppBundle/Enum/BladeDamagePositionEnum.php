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
    const VALVE_BOTH     = 2;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::VALVE_PRESSURE => 'VP',
            self::VALVE_SUCTION  => 'VS',
            self::VALVE_BOTH     => 'C',
        );
    }

    /**
     * @return array
     */
    public static function getLongTextEnumArray()
    {
        return array(
            self::VALVE_PRESSURE => 'Valva pressió',
            self::VALVE_SUCTION  => 'Valva succió',
            self::VALVE_BOTH     => 'Canto',
        );
    }
}
