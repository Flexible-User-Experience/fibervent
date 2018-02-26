<?php

namespace AppBundle\Service;

use Symfony\Component\Form\Form;

/**
 * Class NotificationService.
 *
 * @category Service
 *
 * @author   David Romaní <david@flux.cat>
 */
class NotificationService
{
    /**
     * @var CourierService
     */
    private $messenger;

    /**
     * Methods.
     */

    /**
     * NotificationService constructor.
     *
     * @param CourierService $messenger
     */
    public function __construct(CourierService $messenger)
    {
        $this->messenger = $messenger;
    }

    /**
     * Deliver PDF Audit.
     *
     * @param Form   $form
     * @param string $attatchmentPath
     */
    public function deliverAuditEmail(Form $form, $attatchmentPath)
    {
        $to = $form->get('to')->getData();
        $cc = $form->get('cc')->getData();
        $subject = $form->get('subject')->getData();
        $message = $form->get('message')->getData();

        $this->messenger->sendEmailWithCCAndAttatchment(
            'info@fibervent.com',
            $to,
            $subject,
            $message,
            $cc,
            $attatchmentPath
        );
    }
}
