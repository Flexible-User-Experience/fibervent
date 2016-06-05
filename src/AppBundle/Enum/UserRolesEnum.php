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
            self::ROLE_USER => 'backend.admin.user.user',
            self::ROLE_CMS => 'backend.admin.user.editor',
            self::ROLE_ADMIN => 'backend.admin.user.admin',
            self::ROLE_SUPER_ADMIN => 'backend.admin.user.superadmin',
        );
    }
}
