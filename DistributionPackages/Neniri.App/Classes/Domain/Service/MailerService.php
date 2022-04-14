<?php
namespace Neniri\App\Domain\Service;

/*
 * This file is part of the Neniri.App package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Eel\FlowQuery\FlowQuery;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Neos\FluidAdaptor\View\StandaloneView;

/**
 * Service for sending mails
 *
 * @author Rene Rehme <contact@renerehme.de>
 * @Flow\Scope("singleton")
 * @api
 */
class MailerService
{

    #[Flow\InjectConfiguration(path: 'mailer.useSendmail')]
    protected bool $useSendmail;

    #[Flow\InjectConfiguration(path: 'mailer.smtp')]
    protected array $smtp;


    /**
     *  Send a mail
     *
     *  Example:
     *  $mailerProperties = array(
     *      'from' => 'no-reply@neniri.de',
     *      'to' => 'test@neniri.de',
     *      'replyTo' => '',
     *      'cc' => '',
     *      'bcc' => '',
     *      'subject' => 'Subject',
     *      'body' => $fluid->render(),
     *  );
     *
     * @param array $props
     * @return void
     */
    public function send(array $props): void
    {
        // define default dsn
        $dsn = 'sendmail://default';
        // use smtp if sendmail is disabled in settings
        if(!$this->useSendmail) {
            $dsn = 'smtp://'.$this->smtp['user'].':'.$this->smtp['password'].'@'.$this->smtp['host'].':'.$this->smtp['port'];
        }

        // create mailer
        $mailer = new Mailer(Transport::fromDsn($dsn));

        // create email
        $email = new Email();
        $email->from(new Address($props['from']));
        $email->to(new Address($props['to']));
        $email->subject($props['subject']);
        $email->html($props['body']);

        if (!empty($props['cc'])) {
            $email->cc(new Address($props['cc']));
        }
        if (!empty($props['bcc'])) {
            $email->bcc(new Address($props['bcc']));
        }
        if (!empty($props['replyTo'])) {
            $email->replyTo(new Address($props['replyTo']));
        }

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending
        }
    }
}