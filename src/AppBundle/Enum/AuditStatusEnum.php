<?php

namespace AppBundle\Enum;

/**
 * AuditStatusEnum class.
 *
 * @category Enum
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class AuditStatusEnum
{
    const PENDING = 0;
    const DOING = 1;
    const DONE = 2;
    const INVOICED = 3;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::PENDING => 'enum.audit_status.pending',
            self::DOING => 'enum.audit_status.doing',
            self::DONE => 'enum.audit_status.done',
            self::INVOICED => 'enum.audit_status.invoiced',
        );
    }

    /**
     * @return array
     */
    public static function getReversedEnumArray()
    {
        return array(
            'enum.audit_status.pending' => self::PENDING,
            'enum.audit_status.doing' => self::DOING,
            'enum.audit_status.done' => self::DONE,
            'enum.audit_status.invoiced' => self::INVOICED,
        );
    }
}
