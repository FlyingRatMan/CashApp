<?php
declare(strict_types=1);

namespace App\Components\Mailer\Business;

use App\Components\Mailer\Business\Model\MailService;

class MailerFacade
{
    public function __construct(
        private MailService $mailService
    ) {}
    public function sendEmail(string $email): void
    {
        $this->mailService->send($email);
    }
}