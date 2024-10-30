<?php
declare(strict_types=1);

namespace Unit\Components\UserForgetPassword;

use App\Components\Mailer\Business\MailerFacade;
use App\Components\Token\Business\TokenFacade;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\UserForgetPassword\Business\UserForgetPasswordFacade;
use App\DataTransferObjects\UserDTO;
use PHPUnit\Framework\TestCase;

class UserForgetPasswordFacadeTest extends TestCase
{
    private UserForgetPasswordFacade $facade;
    private UserBusinessFacade $userBusinessFacadeMock;
    private MailerFacade $mailerFacadeMock;
    private TokenFacade $tokenFacadeMock;

    protected function setUp(): void
    {
        $this->userBusinessFacadeMock = $this->createMock(UserBusinessFacade::class);
        $this->mailerFacadeMock = $this->createMock(MailerFacade::class);
        $this->tokenFacadeMock = $this->createMock(TokenFacade::class);

        $this->facade = new UserForgetPasswordFacade(
            $this->userBusinessFacadeMock,
            $this->mailerFacadeMock,
            $this->tokenFacadeMock
        );
    }

    public function testSaveEmailValid(): void
    {
        $email = 'test@test.com';
        $user = new UserDTO(1, 'name', 'test@eamil.com', $email);

        $this->userBusinessFacadeMock
            ->expects($this->once())
            ->method('getUserByEmail')
            ->with($email)
            ->willReturn(null);
    }
}