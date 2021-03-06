<?php

namespace AppBundle\Enum;

/**
 * RepairWindmillSectionEnum class.
 *
 * @category Enum
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 */
class RepairWindmillSectionEnum
{
    const BLADE = 0;
    const NACELLE = 1;
    const TOWER = 2;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::BLADE => 'enum.repair_windmill_section.blade',
            self::NACELLE => 'enum.repair_windmill_section.nacelle',
            self::TOWER => 'enum.repair_windmill_section.tower',
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
