<?php

namespace AppBundle\Enum;

/**
 * UserRolesEnum class.
 *
 * @category Enum
 *
 * @author   David Romaní <david@flux.cat>
 */
class UserRolesEnum
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_CUSTOMER = 'ROLE_CUSTOMER';
    const ROLE_OPERATOR = 'ROLE_OPERATOR';
    const ROLE_TECHNICIAN = 'ROLE_TECHNICIAN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::ROLE_USER => 'enum.user_roles.user',
            self::ROLE_CUSTOMER => 'enum.user_roles.customer',
            self::ROLE_OPERATOR => 'enum.user_roles.operator',
            self::ROLE_TECHNICIAN => 'enum.user_roles.technician',
            self::ROLE_ADMIN => 'enum.user_roles.admin',
            self::ROLE_SUPER_ADMIN => 'enum.user_roles.super_admin',
        );
    }
}
