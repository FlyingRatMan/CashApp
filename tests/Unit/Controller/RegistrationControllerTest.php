<?php
/*declare(strict_types=1);

namespace Unit\Controller;

use App\Controller\RegistrationController;
use App\Core\View;
use App\Model\DB\SqlConnector;
use App\Model\User\UserEntityManager;
use App\Model\User\UserMapper;
use App\Model\User\UserRepository;
use App\Service\UserValidator;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class RegistrationControllerTest extends TestCase
{
    private View $view;
    private RegistrationController $registrationController;

    protected function setUp(): void
    {
        parent::setUp();

        $loader = new FilesystemLoader('src/View/templates');
        $twig = new Environment($loader);

        $this->view = new View($twig);
        $sqlConnector = new SqlConnector();
        $userEntityManager = new UserEntityManager($sqlConnector);
        $userRepository = new UserRepository($sqlConnector);
        $userValidator = new UserValidator();
        $userMapper = new UserMapper();
        $this->registrationController = new RegistrationController($this->view, $userEntityManager, $userRepository, $userValidator, $userMapper);
    }

    public function testIndex(): void
    {
        $_SERVER['REQUEST_METHOD'] = false;

        $this->registrationController->index();

        $template = $this->view->getTemplate();

        $this->assertSame('register.twig', $template);
    }

    public function testIndexPostValidUser(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['name'] = 'test';
        $_POST['email'] = 'new.test@test.com';
        $_POST['password'] = '12QWqw,.';

        $this->registrationController->index();

        $redirect = $this->view->getRedirectTo();

        $this->assertSame('Location: /index.php?page=login', $redirect);
    }

    public function testIndexPostInvalidUser(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['name'] = 'test';
        $_POST['email'] = 'invalid@.com';
        $_POST['password'] = '123';

        $this->registrationController->index();

        $redirect = $this->view->getRedirectTo();

        $this->assertEmpty($redirect);
    }
}*/