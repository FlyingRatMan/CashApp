<?php
/*declare(strict_types=1);

namespace Unit\Controller;

use App\Controller\ForgotPasswordController;
use App\Core\View;
use App\Model\DB\SqlConnector;
use App\Model\User\UserRepository;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ForgotPasswordControllerTest extends TestCase
{
    private SqlConnector $sqlConnector;
    private View $view;
    private UserRepository $userRepository;
    private ForgotPasswordController $forgotPasswordController;

    protected function setUp(): void
    {
        parent::setUp();

        $loader = new FilesystemLoader('src/View/templates');
        $twig = new Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);

        $this->view = new View($twig);
        $this->sqlConnector = new SqlConnector();
        $this->userRepository = new UserRepository($this->sqlConnector);
        $this->forgotPasswordController = new ForgotPasswordController(
            $this->view,
            $this->sqlConnector,
            $this->userRepository
        );

        $insertUserQuery = "INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)";
        $this->sqlConnector->insert($insertUserQuery, [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=0", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Users", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Reset_password_tokens", []);
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=1", []);

        parent::tearDown();
    }

    public function testIndex(): void
    {
        $_POST['sendEmail'] = true;
        $_POST['email'] = 'test@example.com';

        $this->forgotPasswordController->index();

        $emails = file_get_contents('http://localhost:1080/messages');
        $emails = json_decode($emails, true);
        $lastEmail = $emails[count($emails) - 1];

        $this->assertNotEmpty($emails);
        $this->assertSame('<test@example.com>', $lastEmail['recipients'][0]);
        $this->assertSame('Reset Password', $lastEmail['subject']);
    }
}*/