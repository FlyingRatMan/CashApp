<?php
declare(strict_types=1);

namespace Unit\Controller;

use App\Controller\LoginController;
use App\Core\View;
use App\Model\DB\SqlConnector;
use App\Model\User\UserDTO;
use App\Model\User\UserEntityManager;
use App\Model\User\UserRepository;
use App\Service\UserValidator;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class LoginControllerTest extends TestCase
{
    private View $view;
    private UserEntityManager $userEntityManager;
    private LoginController $loginController;

    public function setUp(): void
    {
        parent::setUp();

        $loader = new FilesystemLoader('src/View/templates');
        $twig = new Environment($loader);

        $this->view = new View($twig);
        $sqlConnector = new SqlConnector();
        $userRepository = new UserRepository($sqlConnector);
        $this->userEntityManager = new UserEntityManager($sqlConnector);
        $userValidator = new UserValidator();
        $this->loginController = new LoginController($this->view, $userRepository, $userValidator);
    }

    public function testIndexInvalidUser(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['email'] = 'invalidEmail';
        $_POST['password'] = 'invalidPassword';

        $this->loginController->index();

        $parameters = $this->view->getParameters();

        $this->assertNotEmpty($parameters['err']);
    }

    public function testIndexValidUser(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['email'] = 'test@test.com';
        $_POST['password'] = 'test';

        $this->userEntityManager
            ->save(new UserDTO(1, 'test', 'test@test.com', password_hash('test', PASSWORD_DEFAULT)));

        $this->loginController->index();

        $redirect = $this->view->getRedirectTo();

        $this->assertSame('Location: /', $redirect);
    }
}