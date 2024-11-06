<?php
declare(strict_types=1);

namespace Integration\UserLogin;

use App\Components\User\Business\Model\UserValidation;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserLogin\Business\UserLoginFacade;
use App\Components\UserLogin\Communication\UserLoginController;
use App\Core\View;
use App\db_script;
use App\Model\DB\SqlConnector;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserLoginControllerTest extends TestCase
{
    private UserLoginController $loginController;

    public function setUp(): void
    {
        parent::setUp();

        unset($_POST['login'], $_POST['reset']);

        $loader = new FilesystemLoader('/home/olhapurtova/PhpstormProjects/CashApp/src/View/templates');
        $twig = new Environment($loader);

        $view = new View($twig);
        $sqlConnector = new SqlConnector();
        $userMapper = new UserMapper();
        $userRepository = new UserRepository($userMapper, $sqlConnector);
        $userEntityManager = new UserEntityManager($sqlConnector);
        $userFacade = new UserBusinessFacade($userRepository, $userEntityManager);
        $userValidation = new UserValidation($userFacade);
        $loginFacade = new UserLoginFacade($view, $userValidation);
        $this->loginController = new UserLoginController($view, $loginFacade);

        $db_script = new db_script();
        $db_script->prefillDatabase();
    }

    public function testIndexCallsLogin(): void
    {
        $_POST['login'] = true;
        $_POST['email'] = 'erika@example.com';
        $_POST['password'] = '12QWqw,.';

        $this->loginController->index();
        $userName = $_SESSION['loggedUser'];

        $this->assertSame('Erika Mustermann', $userName);
    }
}