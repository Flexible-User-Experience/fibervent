<?php

namespace AppBundle\Enum;

/**
 * Class BladeDamageStatusEnum.
 *
 * @category Enum
 *
 * @author   David Romaní <david@flux.cat>
 */
class BladeDamageStatusEnum
{
    const STATUS_NOT_VALIDATED = 0;
    const STATUS_VALIDATED = 1;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::STATUS_NOT_VALIDATED => 'No validat',
            self::STATUS_VALIDATED => 'Validat',
        );
    }
}
