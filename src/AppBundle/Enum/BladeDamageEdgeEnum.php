<?php

namespace AppBundle\Enum;

use AppBundle\Entity\BladeDamage;

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
            self::EDGE_IN  => 'BA',
            self::EDGE_OUT => 'BS',
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

    /**
     * @param BladeDamage $bladeDamage
     *
     * @return string
     */
    public static function getStringValue(BladeDamage $bladeDamage)
    {
        return self::getEnumArray()[$bladeDamage->getEdge()];
    }
}
