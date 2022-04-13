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
     * @param array $mailerProps
     * @return void
     */
    public function send(array $mailerProps): void
    {
        if($this->useSendmail) {
            $transport = Transport::fromDsn('sendmail://default');
        } else {
            $dsn = 'smtp://'.$this->smtp['user'].':'.$this->smtp['password'].'@'.$this->smtp['host'].':'.$this->smtp['port'];
            $transport = Transport::fromDsn($dsn);
        }

        $mailer = new Mailer($transport);

        $email = new Email();
        $email->from(new Address($mailerProps['from']));
        $email->to(new Address($mailerProps['to']));
        if (!empty($mailerProps['cc'])) {
            $email->cc(new Address($mailerProps['cc']));
        }
        if (!empty($mailerProps['bcc'])) {
            $email->bcc(new Address($mailerProps['bcc']));
        }
        if (!empty($mailerProps['replyTo'])) {
            $email->replyTo(new Address($mailerProps['replyTo']));
        }
        $email->subject($mailerProps['subject']);
        $email->html($mailerProps['body']);

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending
        }
    }
}