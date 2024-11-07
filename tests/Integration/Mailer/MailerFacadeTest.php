<?php
declare(strict_types=1);

namespace Integration\Mailer;

use App\Components\Mailer\Business\MailerFacade;
use App\Components\Mailer\Business\Model\MailService;
use App\DataTransferObjects\MailDTO;
use PHPUnit\Framework\TestCase;

class MailerFacadeTest extends TestCase
{
    private MailerFacade $mailerFacade;

    protected function setUp(): void
    {
        $mailService = new MailService();
        $this->mailerFacade = new MailerFacade($mailService);
    }

    public function testSendEmailCallsService(): void
    {
        $mailDTO = new MailDTO('test@mail.send', 'user@email.com', 'Test Send Email', 'Test Message');

        $this->mailerFacade->sendEmail($mailDTO);

        $messages = json_decode(file_get_contents('http://localhost:1080/messages'), true);
        $lastMessage = end($messages);

        $this->assertIsArray($messages);
        $this->assertNotEmpty($messages);
        $this->assertSame('<test@mail.send>', $lastMessage['sender']);
        $this->assertSame('<user@email.com>', $lastMessage['recipients'][0]);
    }
}