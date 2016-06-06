<?php

namespace AppBundle\Enum;

/**
 * UserRolesEnum class
 *
 * @category Enum
 * @package  AppBundle\Enum
 * @author   David Romaní <david@flux.cat>
 */
class UserRolesEnum
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_CMS = 'ROLE_CMS';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::ROLE_USER => 'Usuari',
            self::ROLE_CMS => 'Editor',
            self::ROLE_ADMIN => 'Administrador',
            self::ROLE_SUPER_ADMIN => 'Superadministrador',
        );
    }
}
