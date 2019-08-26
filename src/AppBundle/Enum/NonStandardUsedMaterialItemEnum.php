<?php

namespace AppBundle\Enum;

/**
 * NonStandardUsedMaterialItemEnum class.
 *
 * @category Enum
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 */
class NonStandardUsedMaterialItemEnum
{
    const OTHERS = 0;
    const RECEIVERS = 1;
    const VORTEX = 2;
    const STALL_STRIP = 3;
    const DIVERTER_S = 4;
    const BELT_3M = 5;
    const LEP = 6;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::OTHERS => 'Altres',
            self::RECEIVERS => 'Receptors',
            self::VORTEX => 'Vortex',
            self::STALL_STRIP => 'Stall strip',
            self::DIVERTER_S => 'Diverter S.',
            self::BELT_3M => 'Cinta 3m',
            self::LEP => 'LEP',
        );
    }
}
