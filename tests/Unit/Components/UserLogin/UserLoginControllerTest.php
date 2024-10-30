<?php
declare(strict_types=1);

namespace Unit\Components\UserLogin;

use App\Components\User\Business\Model\UserValidation;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserLogin\Business\UserLoginFacade;
use App\Components\UserLogin\Communication\UserLoginController;
use App\Core\View;
use App\Model\DB\SqlConnector;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserLoginControllerTest extends TestCase
{
    private View $view;
    private UserMapper $userMapper;
    private SqlConnector $sqlConnector;
    private UserEntityManager $userEntityManager;
    private UserLoginController $loginController;

    public function setUp(): void
    {
        parent::setUp();

        unset($_POST['login'], $_POST['reset']);

        $loader = new FilesystemLoader('/home/olhapurtova/PhpstormProjects/CashApp/src/View/templates');
        $twig = new Environment($loader);

        $this->view = new View($twig);
        $this->sqlConnector = new SqlConnector();
        $this->userMapper = new UserMapper();
        $userRepository = new UserRepository($this->userMapper, $this->sqlConnector);
        $this->userEntityManager = new UserEntityManager($this->sqlConnector);
        $userFacade = new UserBusinessFacade($userRepository, $this->userEntityManager);
        $userValidation = new UserValidation($userFacade);
        $loginFacade = new UserLoginFacade($this->view, $userValidation);
        $this->loginController = new UserLoginController($this->view, $loginFacade);
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=0", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Users", []);
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=1", []);

        parent::tearDown();
    }

    public function testIndexOnResetPassword(): void
    {
        $_POST['reset'] = true;

        $this->loginController->index();
        $redirect = $this->view->getRedirectTo();

        $this->assertSame('Location: /index.php?page=forgotPassword', $redirect);
    }

    public function testIndex(): void
    {
        $this->loginController->index();
        $template = $this->view->getTemplate();

        $this->assertSame('login.twig', $template);
    }
}