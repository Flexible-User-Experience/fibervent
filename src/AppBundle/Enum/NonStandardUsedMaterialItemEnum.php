<?php

namespace AppBundle\Enum;

use AppBundle\Entity\NonStandardUsedMaterial;

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
            self::OTHERS => 'enum.non_standard_used_material_item.others',
            self::RECEIVERS => 'enum.non_standard_used_material_item.receivers',
            self::VORTEX => 'enum.non_standard_used_material_item.vortex',
            self::STALL_STRIP => 'enum.non_standard_used_material_item.stall_strip',
            self::DIVERTER_S => 'enum.non_standard_used_material_item.diverter_s',
            self::BELT_3M => 'enum.non_standard_used_material_item.belt_3m',
            self::LEP => 'enum.non_standard_used_material_item.lep',
        );
    }

    /**
     * @param NonStandardUsedMaterial $nonStandardUsedMaterial
     *
     * @return string
     */
    public static function getStringValue(NonStandardUsedMaterial $nonStandardUsedMaterial)
    {
        return self::getEnumArray()[$nonStandardUsedMaterial->getItem()];
    }
}
