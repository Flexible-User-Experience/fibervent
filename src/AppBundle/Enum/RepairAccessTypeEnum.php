<?php

namespace AppBundle\Enum;

/**
 * RepairAccessTypeEnum class.
 *
 * @category Enum
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 */
class RepairAccessTypeEnum
{
    const CRANE = 0;
    const BASKET_CRANE = 1;
    const ROPES = 2;
    const GROUND = 3;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::CRANE => 'Grua',
            self::BASKET_CRANE => 'Cistella FV',
            self::ROPES => 'Cordes',
            self::GROUND => 'Terra',
        );
    }
}
