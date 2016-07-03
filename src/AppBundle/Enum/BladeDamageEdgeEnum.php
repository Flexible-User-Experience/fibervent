<?php

namespace AppBundle\Enum;

/**
 * Class BladeDamageEdgeEnum
 *
 * @category Enum
 * @package  AppBundle\Enum
 * @author   David Romaní <david@flux.cat>
 */
class BladeDamageEdgeEnum
{
    const EDGE_IN  = 0;
    const EDGE_OUT = 1;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::EDGE_IN  => 'CA',
            self::EDGE_OUT => 'CS',
        );
    }

    /**
     * @return array
     */
    public static function getLongTextEnumArray()
    {
        return array(
            self::EDGE_IN  => 'Atac',
            self::EDGE_OUT => 'Sortida',
        );
    }
}
