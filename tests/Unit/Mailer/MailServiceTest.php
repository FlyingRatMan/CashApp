<?php
declare(strict_types=1);

namespace Unit\Mailer;

use App\Components\Mailer\Business\Model\MailService;
use App\DataTransferObjects\MailDTO;
use PHPUnit\Framework\TestCase;

class MailServiceTest extends TestCase
{
    private MailService $mailService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mailService = new MailService();
    }

    public function testSendSuccessful(): void
    {
        $mailDto = new MailDto('test@mail.send', 'user@email.com', 'Test Send Email', 'Test Message');

        $this->mailService->send($mailDto);
        $messages = json_decode(file_get_contents('http://localhost:1080/messages'), true);
        $lastMessage = end($messages);

        $this->assertIsArray($messages);
        $this->assertNotEmpty($messages);
        $this->assertSame('<test@mail.send>', $lastMessage['sender']);
        $this->assertSame('<user@email.com>', $lastMessage['recipients'][0]);
    }
}