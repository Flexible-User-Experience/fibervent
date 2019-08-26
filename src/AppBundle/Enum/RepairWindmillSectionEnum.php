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
            self::BLADE => 'Pala',
            self::NACELLE => 'Nacelle',
            self::TOWER => 'Torre',
        );
    }
}
