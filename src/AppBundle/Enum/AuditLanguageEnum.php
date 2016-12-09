<?php

namespace AppBundle\Enum;

/**
 * Class AuditLanguageEnum
 *
 * @category Enum
 * @package  AppBundle\Enum
 * @author   David Romaní <david@flux.cat>
 */
class AuditLanguageEnum
{
    const SPANISH    = 0;
    const ENGLISH    = 1;
    const FRENCH     = 2;
    const PORTUGUESE = 3;
    const GERMAN     = 4;
    const ITALIAN    = 5;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::SPANISH    => 'es',
            self::ENGLISH    => 'en',
            self::FRENCH     => 'fr',
            self::PORTUGUESE => 'pt',
            self::GERMAN     => 'de',
            self::ITALIAN    => 'it',
        );
    }

    /**
     * @return array
     */
    public static function getEnumArrayString()
    {
        return array(
            self::SPANISH    => 'español',
            self::ENGLISH    => 'english',
            self::FRENCH     => 'français',
//            self::PORTUGUESE => 'portuguès',
//            self::GERMAN     => 'alemany',
//            self::ITALIAN    => 'italià',
        );
    }
}
