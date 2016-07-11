<?php

namespace AppBundle\Service;

use \Swift_Message;
use \Swift_Attachment;

/**
 * Class CourierService
 *
 * @category Service
 * @package  AppBundle\Service
 * @author   David Romaní <david@flux.cat>
 */
class CourierService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     *
     *
     * Methods
     *
     *
     */

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
        $message = $this->commonSendEmail($from, $to, $subject, $body);

        $this->mailer->send($message);
    }

    /**
     * Send an email with CC and attatchment
     *
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param string $cc
     * @param string $attatchmentPath
     */
    public function sendEmailWithCCAndAttatchment($from, $to, $subject, $body, $cc, $attatchmentPath)
    {
        $message = $this->commonSendEmail($from, $to, $subject, $body);
        $message->attach(\Swift_Attachment::fromPath($attatchmentPath));
        if (!is_null($cc)) {
            $message->addCc($cc);
        }

        $this->mailer->send($message);
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $body
     *
     * @return Swift_Message
     */
    private function commonSendEmail($from, $to, $subject, $body)
    {
        $message = new \Swift_Message();
        $message
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body)
            ->setCharset('UTF-8')
            ->setContentType('text/html');

        return $message;
    }
}
