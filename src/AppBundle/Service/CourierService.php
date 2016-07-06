<?php

namespace AppBundle\Service;

use \Swift_Message;

/**
 * Class CourierService
 *
 * @category Service
 * @package  AppBundle\Service
 * @author   David Romaní <david@flux.cat>
 */
class CourierService
{
    /** @var \Swift_Mailer */
    private $mailer;

    /**
     * CourierService constructor
     *
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send an email
     *
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $body
     */
    public function sendEmail($from, $to, $subject, $body)
    {
        $message = new \Swift_Message();
        $message
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body)
            ->setCharset('UTF-8')
            ->setContentType('text/html');

        $this->mailer->send($message);
    }
}
