<?php
declare(strict_types=1);

namespace App\Components\Mailer\Business\Model;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\Transports;
use Symfony\Component\Mime\Email;

class MailService
{
    public function send(string $email): void
    {
        $mailer = new Mailer(new Transports(['main' => new EsmtpTransport('localhost', 1025)]));
        $resetLink = 'http://localhost:8080/index.php?page=resetPassword&token=' . bin2hex($email);

        $mail = (new Email())
            ->from('cash@cash.de')
            ->to($email)
            ->subject('Reset Password')
            ->html('
                    <a href="' . $resetLink . '">Click here to reset password</a>
                    ');

        $mailer->send($mail);
    }
}