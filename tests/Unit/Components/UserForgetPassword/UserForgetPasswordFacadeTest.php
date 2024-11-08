<?php
declare(strict_types=1);

namespace Unit\Components\UserForgetPassword;

use App\Components\Mailer\Business\MailerFacade;
use App\Components\Mailer\Mapper\MailerMapper;
use App\Components\Token\Business\TokenFacade;
use App\Components\Token\Persistence\Mapper\TokenMapper;
use App\Components\Token\Persistence\TokenEntityManager;
use App\Components\Token\Persistence\TokenRepository;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserForgetPassword\Business\UserForgetPasswordFacade;
use App\Model\DB\ORMEntityManager;
use PHPUnit\Framework\TestCase;

class UserForgetPasswordFacadeTest extends TestCase
{
    private UserForgetPasswordFacade $facade;
    private TokenRepository $tokenRepository;

    protected function setUp(): void
    {
        $sqlConnector = new ORMEntityManager();
        $userMapper = new UserMapper();
        $userRepository = new UserRepository($userMapper, $sqlConnector);
        $userEntityManager = new UserEntityManager($sqlConnector);
        $userBusinessFacade = new UserBusinessFacade($userRepository, $userEntityManager);
        $mailerFacadeMock = $this->createMock(MailerFacade::class);
        $tokenMapper = new TokenMapper();
        $this->tokenRepository = new TokenRepository($tokenMapper);
        $tokenEntityManager = new TokenEntityManager($sqlConnector);
        $tokenFacade = new TokenFacade($this->tokenRepository, $tokenEntityManager, $tokenMapper);
        $mailerMapper = new MailerMapper();

        $this->facade = new UserForgetPasswordFacade(
            $userBusinessFacade,
            $mailerFacadeMock,
            $tokenFacade,
            $mailerMapper
        );
    }

    public function testSaveTokenSavesNewToken(): void
    {
        $email = 'new@example.com';

        $this->facade->saveToken($email);
        $actual = $this->tokenRepository->getTokenByEmail($email);

        $this->assertSame($email, $actual->email);
    }

    public function testSaveTokenUpdatesToken(): void
    {
        $email = 'update@example.com';
        $date = date('Y-m-d H:i:s', time() + 120 * 60);

        $this->facade->saveToken($email);
        $actual = $this->tokenRepository->getTokenByEmail($email);

        $this->assertSame($date, $actual->expires_at);
    }
}