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
use App\Model\DB\SqlConnector;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserLoginFacadeTest extends TestCase
{
    private UserEntityManager $userEntityManager;
    private UserLoginFacade $userLoginFacade;
    private UserMapper $userMapper;
    private View $view;

    protected function setUp(): void
    {
        parent::setUp();

        $loader = new FilesystemLoader('/home/olhapurtova/PhpstormProjects/CashApp/src/View/templates');
        $twig = new Environment($loader);

        $this->view = new View($twig);
        $this->userMapper = new UserMapper();
        $sqlConnector = new SqlConnector();
        $userRepository = new UserRepository($this->userMapper, $sqlConnector);
        $this->userEntityManager = new UserEntityManager($sqlConnector);
        $userFacade = new UserBusinessFacade($userRepository, $this->userEntityManager);
        $userValidation = new UserValidation($userFacade);
        $this->userLoginFacade = new UserLoginFacade($this->view, $userValidation);
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
        $email = 'test@test.com';
        $password = 'test';
        $user = $this->userMapper->createUserDTO(['id'=>1, 'name'=>'test', 'email'=>'test@test.com', 'password'=>password_hash('test', PASSWORD_DEFAULT)]);

        $this->userEntityManager->save($user);

        $this->userLoginFacade->login($email, $password);

        $redirect = $this->view->getRedirectTo();

        $this->assertSame('Location: /', $redirect);
    }
}