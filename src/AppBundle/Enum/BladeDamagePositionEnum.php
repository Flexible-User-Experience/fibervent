<?php

namespace AppBundle\Enum;

use AppBundle\Entity\BladeDamage;

/**
 * Class BladeDamagePositionEnum.
 *
 * @category Enum
 *
 * @author   David Romaní <david@flux.cat>
 */
class BladeDamagePositionEnum
{
    const VALVE_PRESSURE = 0;
    const VALVE_SUCTION = 1;
    const EDGE_IN = 2;
    const EDGE_OUT = 3;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::VALVE_PRESSURE => 'VP',
            self::VALVE_SUCTION => 'VS',
            self::EDGE_IN => 'BA',
            self::EDGE_OUT => 'BS',
        );
    }

    /**
     * @return array
     */
    public static function getLocalizedEnumArray()
    {
        return array(
            self::VALVE_PRESSURE => 'enum.blade_damage_position.1_VP',
            self::VALVE_SUCTION => 'enum.blade_damage_position.2_VS',
            self::EDGE_IN => 'enum.blade_damage_position.3_BA',
            self::EDGE_OUT => 'enum.blade_damage_position.4_BS',
        );
    }

    /**
     * @return array
     */
    public static function getLongTextEnumArray()
    {
        return array(
            self::VALVE_PRESSURE => 'Valva pressió',
            self::VALVE_SUCTION => 'Valva succió',
            self::EDGE_IN => 'Vora atac',
            self::EDGE_OUT => 'Vora sortida',
        );
    }

    /**
     * @param BladeDamage $bladeDamage
     *
     * @return string
     */
    public static function getStringValue(BladeDamage $bladeDamage)
    {
        return self::getEnumArray()[$bladeDamage->getPosition()];
    }

    /**
     * @param BladeDamage $bladeDamage
     *
     * @return string
     */
    public static function getStringLocalizedValue(BladeDamage $bladeDamage)
    {
        return self::getLocalizedEnumArray()[$bladeDamage->getPosition()];
    }
}
