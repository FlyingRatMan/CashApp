<?php
declare(strict_types=1);

namespace App\Components\Mailer\Business\Model;

use App\DataTransferObjects\MailDTO;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\Transports;
use Symfony\Component\Mime\Email;

class MailService
{
    private Mailer $mailer;

    public function __construct()
    {
        $transport = new Transports(['main' => new EsmtpTransport('localhost', 1025)]);
        $this->mailer = new Mailer($transport);
    }

    public function send(MailDTO $mail): void
    {
        $email = (new Email())
            ->from($mail->from)
            ->to($mail->to)
            ->subject($mail->subject)
            ->html($mail->html);

        $this->mailer->send($email);
    }
}