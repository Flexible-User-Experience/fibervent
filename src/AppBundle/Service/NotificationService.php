<?php

namespace AppBundle\Service;

/**
 * Class NotificationService
 *
 * @category Service
 * @package  AppBundle\Service
 * @author   David Romaní <david@flux.cat>
 */
class NotificationService
{
    /**
     * @var CourierService
     */
    private $messenger;

    /**
     * NotificationService constructor
     *
     * @param CourierService    $messenger
     */
    public function __construct(CourierService $messenger)
    {
        $this->messenger = $messenger;
    }

    /**
     * Deliver PDF Audit
     *
     * @param string $from
     * @param string $to
     * @param string $cc
     * @param string $subject
     * @param string $message
     * @param string $attatchmentPath
     */
    public function deliverAuditEmail($from, $to, $cc, $subject, $message, $attatchmentPath)
    {
        $this->messenger->sendEmailWithCCAndAttatchment(
            $from,
            $to,
            $subject,
            $message,
            $cc,
            $attatchmentPath
        );
    }
}
