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
    const ROLE_USER        = 'ROLE_USER';
    const ROLE_CUSTOMER    = 'ROLE_CUSTOMER';
    const ROLE_OPERATOR    = 'ROLE_OPERATOR';
    const ROLE_TECHNICIAN  = 'ROLE_TECHNICIAN';
    const ROLE_ADMIN       = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::ROLE_USER        => 'Usuari',
            self::ROLE_CUSTOMER    => 'Client',
            self::ROLE_OPERATOR    => 'Operari',
            self::ROLE_TECHNICIAN  => 'Tècnic',
            self::ROLE_ADMIN       => 'Administrador',
            self::ROLE_SUPER_ADMIN => 'Superadministrador',
        );
    }
}
