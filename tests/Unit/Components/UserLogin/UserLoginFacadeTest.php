<?php
declare(strict_types=1);

namespace Unit\Components\UserLogin;

use App\Components\User\Business\Model\UserValidation;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserLogin\Business\UserLoginFacade;
use App\Core\View;
use App\db_script;
use App\Model\DB\ORMEntityManager;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserLoginFacadeTest extends TestCase
{
    private UserLoginFacade $userLoginFacade;
    private View $view;
    private db_script $db_script;

    protected function setUp(): void
    {
        parent::setUp();

        $loader = new FilesystemLoader('/home/olhapurtova/PhpstormProjects/CashApp/src/View/templates');
        $twig = new Environment($loader);

        $this->view = new View($twig);
        $userMapper = new UserMapper();
        $sqlConnector = new ORMEntityManager();
        $userRepository = new UserRepository($userMapper, $sqlConnector);
        $userEntityManager = new UserEntityManager($sqlConnector);
        $userFacade = new UserBusinessFacade($userRepository, $userEntityManager);
        $userValidation = new UserValidation($userFacade);
        $this->userLoginFacade = new UserLoginFacade($this->view, $userValidation);

        $this->db_script = new db_script();
        $this->db_script->prefillDatabase();
    }

    public function testLoginInvalid(): void
    {
        $email = 'invalidEmail';
        $password = 'invalidPassword';

        $this->userLoginFacade->login($email, $password);

        $parameters = $this->view->getParameters();

        $this->assertNotEmpty($parameters['err']);
    }

    public function testLoginValid(): void
    {
        $email = 'erika@example.com';
        $password = '12QWqw,.';

        $this->userLoginFacade->login($email, $password);

        $redirect = $this->view->getRedirectTo();

        $this->assertSame('Location: /', $redirect);
    }
}