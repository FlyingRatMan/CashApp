<?php
declare(strict_types=1);

namespace Unit\Controller;

use App\Controller\ResetPasswordController;
use App\Core\View;
use App\Model\DB\SqlConnector;
use App\Model\User\UserEntityManager;
use App\Model\User\UserRepository;
use App\Service\UserValidator;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ResetPasswordControllerTest extends TestCase
{
    private SqlConnector $sqlConnector;
    private View $view;
    private UserEntityManager $userEntityManager;
    private UserRepository $userRepository;
    private UserValidator $userValidator;
    private ResetPasswordController $resetPasswordController;

    protected function setUp(): void
    {
        parent::setUp();

        $loader = new FilesystemLoader('/home/olhapurtova/PhpstormProjects/CashApp/src/View/templates');
        $twig = new Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);

        $this->view = new View($twig);
        $this->sqlConnector = new SqlConnector();
        $this->userEntityManager = new UserEntityManager($this->sqlConnector);
        $this->userRepository = new UserRepository($this->sqlConnector);
        $this->userValidator = new UserValidator();
        $this->resetPasswordController = new ResetPasswordController(
            $this->view,
            $this->sqlConnector,
            $this->userEntityManager,
            $this->userRepository,
            $this->userValidator,
        );
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=0", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Users", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Reset_password_tokens", []);
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=1", []);

        parent::tearDown();
    }

    public function testTokenValidationIsExpired(): void
    {
        $email = 'expired@example.com';
        $insertTokenQuery = "INSERT INTO Reset_password_tokens (token, email, expires_at) VALUES (:token, :email, :expires_at)";
        $this->sqlConnector->insert($insertTokenQuery, [
            'token' => 'test_token',
            'email' => 'expired@example.com',
            'expires_at' => date('2024-10-01 00:20:00'),
        ]);

        $actual = $this->resetPasswordController->tokenValidation($email);

        $this->assertFalse($actual);
    }

    public function testTokenValidationIsValid(): void
    {
        $email = 'valid@example.com';
        $insertTokenQuery = "INSERT INTO Reset_password_tokens (token, email, expires_at) VALUES (:token, :email, :expires_at)";
        $this->sqlConnector->insert($insertTokenQuery, [
            'token' => 'test_token',
            'email' => 'valid@example.com',
            'expires_at' => date('Y-m-d H:i:s', time() + (120 * 60)),
        ]);

        $actual = $this->resetPasswordController->tokenValidation($email);

        $this->assertTrue($actual);
    }

    public function testIndexInvalid(): void
    {
        $token = bin2hex('invalid_token');
        $_GET['token'] = $token;

        $this->resetPasswordController->index();
        $redirect = $this->view->getRedirectTo();

        $this->assertSame('Location: /index.php?page=forgotPassword', $redirect);
    }

    public function testIndexValid(): void
    {
        $token = bin2hex('new.email@example.com');
        $_GET['token'] = $token;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['password'] = '12QWqw,.';

        $insertUserQuery = "INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)";
        $this->sqlConnector->insert($insertUserQuery, [
            'name' => 'Test',
            'email' => 'new.email@example.com',
            'password' => 'password',
        ]);

        $insertTokenQuery = "INSERT INTO Reset_password_tokens (token, email, expires_at) VALUES (:token, :email, :expires_at)";
        $this->sqlConnector->insert($insertTokenQuery, [
            'token' => $token,
            'email' => 'new.email@example.com',
            'expires_at' => date('Y-m-d H:i:s', time() + (120 * 60)),
        ]);

        $this->resetPasswordController->index();

        $parameter = $this->view->getParameters();
        $user = $this->userRepository->getUserByEmail('new.email@example.com');
        $pass = password_verify( $_POST['password'], $user->password);

        $this->assertEmpty($parameter['error']);
        $this->assertTrue($pass);
    }
}