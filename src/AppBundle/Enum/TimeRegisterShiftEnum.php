<?php

namespace AppBundle\Enum;

/**
 * TimeRegisterShiftEnum class.
 *
 * @category Enum
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 */
class TimeRegisterShiftEnum
{
    const MORNING = 0;
    const AFTERNOON = 1;
    const NIGHT = 2;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::MORNING => 'enum.time_register_shift.morning',
            self::AFTERNOON => 'enum.time_register_shift.afternoon',
            self::NIGHT => 'enum.time_register_shift.night',
        );
    }
}
