<?php

namespace AppBundle\Enum;

/**
 * AuditDiagramTypeEnum class.
 *
 * @category Enum
 *
 * @author   David Romaní <david@flux.cat>
 */
class AuditDiagramTypeEnum
{
    const TYPE_1 = 1;
    const TYPE_2 = 2;
    const TYPE_3 = 3;
    const TYPE_4 = 4;
    const TYPE_5 = 5;
    const TYPE_6 = 6;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::TYPE_1 => '1',
            self::TYPE_2 => '2',
            self::TYPE_3 => '3',
            self::TYPE_4 => '4',
            self::TYPE_5 => '5',
            self::TYPE_6 => '6',
        );
    }
}
