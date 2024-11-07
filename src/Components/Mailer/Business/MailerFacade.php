<?php
declare(strict_types=1);

namespace App\Components\Mailer\Business;

use App\Components\Mailer\Business\Model\MailService;
use App\DataTransferObjects\MailDTO;

class MailerFacade
{
    public function __construct(
        private MailService $mailService
    ) {}
    public function sendEmail(MailDTO $mail): void
    {
        $this->mailService->send($mail);
    }
}