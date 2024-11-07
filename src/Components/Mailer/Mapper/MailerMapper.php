<?php
declare(strict_types=1);

namespace App\Components\Mailer\Mapper;

use App\DataTransferObjects\MailDTO;

class MailerMapper
{
    public function createMailDTO(array $data): MailDTO
    {
        return new MailDTO($data['from'], $data['to'], $data['subject'], $data['html']);
    }
}