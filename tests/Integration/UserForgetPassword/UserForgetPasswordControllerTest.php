<?php
declare(strict_types=1);

namespace Integration\UserForgetPassword;

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
use App\Components\UserForgetPassword\Communication\UserForgetPasswordController;
use App\Core\View;
use App\db_script;
use App\DBConnector\ORMEntityManager;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserForgetPasswordControllerTest extends TestCase
{
    private View $view;
    private TokenRepository $tokenRepository;
    private UserForgetPasswordController $controller;
    private db_script $db_script;

    protected function setUp(): void
    {
        $loader = new FilesystemLoader('/home/olhapurtova/PhpstormProjects/CashApp/src/View/templates');
        $twig = new Environment($loader);

        $this->view = new View($twig);
        $sqlConnector = new ORMEntityManager();
        $userMapper = new UserMapper();
        $userRepository = new UserRepository($userMapper);
        $userEntityManager = new UserEntityManager($sqlConnector);
        $userBusinessFacade = new UserBusinessFacade($userRepository, $userEntityManager);
        $mailerFacadeMock = $this->createMock(MailerFacade::class);
        $tokenMapper = new TokenMapper();
        $this->tokenRepository = new TokenRepository($tokenMapper);
        $tokenEntityManager = new TokenEntityManager($sqlConnector);
        $tokenFacade = new TokenFacade($this->tokenRepository, $tokenEntityManager, $tokenMapper);
        $mailerMapper = new MailerMapper();
        $userForgetPasswordFacade = new UserForgetPasswordFacade(
            $userBusinessFacade,
            $mailerFacadeMock,
            $tokenFacade,
            $mailerMapper
        );
        $this->controller = new UserForgetPasswordController($this->view, $userForgetPasswordFacade);

        $this->db_script = new db_script();
        $this->db_script->prefillDatabase();
    }

    protected function tearDown(): void
    {
        $this->db_script->cleanDatabase();

        parent::tearDown();
    }

    public function testIndexCallsSendEmail(): void
    {
        $_POST['sendEmail'] = true;
        $_POST['email'] = 'update@example.com';

        $this->controller->index();
        $actual = $this->tokenRepository->getTokenByEmail('update@example.com');

        $this->assertSame('update@example.com', $actual->email);
    }

    public function testIndexSetsTemplate(): void
    {
        $_POST['sendEmail'] = false;

        $this->controller->index();
        $template = $this->view->getTemplate();

        $this->assertSame('forgotPassword.twig', $template);
    }
}