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
 * @author Rene Rehme <contact@renerehme.de>
 */
class MailerService
{

    /**
     * @var array
     */
    protected array $settings;

    /**
     * Inject settings
     * @param array $settings
     * @return void
     */
    private function injectSettings(array $settings): void
    {
        $this->settings = $settings['mailer'];
    }

    /**
     *  Send a mail
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
     * @param array $mailerProps
     * @return void
     */
    public function send(array $mailerProps): void
    {
        if($this->settings['useSendmail'] === false) {
            $transport = Transport::fromDsn('sendmail://default');
        } else {
            $dsn = 'smtp://'.$this->settings['smtp']['user'].':'.$this->settings['smtp']['password'].'@'.$this->settings['smtp']['host'].':'.$this->settings['smtp']['port'];
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