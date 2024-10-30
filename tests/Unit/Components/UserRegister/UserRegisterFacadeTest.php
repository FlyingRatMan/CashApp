<?php
declare(strict_types=1);

namespace Unit\Components\UserRegister;

use App\Components\User\Business\Model\UserValidation;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserLogin\Business\UserLoginFacade;
use App\Components\UserRegister\Business\UserRegisterFacade;
use App\Core\View;
use App\Model\DB\SqlConnector;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserRegisterFacadeTest extends TestCase
{
    private UserRepository $userRepository;
    private UserEntityManager $userEntityManager;
    private UserRegisterFacade $userRegisterFacade;
    private UserBusinessFacade $userFacade;
    private UserMapper $userMapper;
    private SqlConnector $sqlConnector;
    private View $view;

    protected function setUp(): void
    {
        parent::setUp();

        $loader = new FilesystemLoader('/home/olhapurtova/PhpstormProjects/CashApp/src/View/templates');
        $twig = new Environment($loader);

        $this->view = new View($twig);
        $this->userMapper = new UserMapper();
        $this->sqlConnector = new SqlConnector();
        $this->userRepository = new UserRepository($this->userMapper, $this->sqlConnector);
        $this->userEntityManager = new UserEntityManager($this->sqlConnector);
        $this->userFacade = new UserBusinessFacade($this->userRepository, $this->userEntityManager);
        $userValidation = new UserValidation($this->userFacade);
        $this->userRegisterFacade = new UserRegisterFacade($this->view, $userValidation, $this->userMapper, $this->userEntityManager);

        $this->sqlConnector->insert("INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)",
            ['name' => 'user', 'email' => 'user@test.com', 'password' => password_hash('password', PASSWORD_DEFAULT)]);;
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=0", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Users", []);
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=1", []);

        parent::tearDown();
    }

    public function testRegisterValidUser(): void
    {
        $user = $this->userMapper->createUserDTO(['id' => 1, 'name' => 'user', 'email' => 'newuser@test.com', 'password' => password_hash('12QWqw,.', PASSWORD_DEFAULT)]);

        $this->userRegisterFacade->register($user);

        $actualUser = $this->userRepository->getUserByEmail($user->email);
        $param = $this->view->getParameters();

        $this->assertSame($user->name, $actualUser->name);
        $this->assertEmpty($param);
    }

    public function testRegisterInvalidUser(): void
    {
        $user = $this->userMapper->createUserDTO(['id' => 1, 'name' => 'user', 'email' => 'invalid', 'password' => 'invalid']);

        $this->userRegisterFacade->register($user);

        $param = $this->view->getParameters();

        $this->assertSame('user', $param['userName']);
        $this->assertSame('invalid', $param['userEmail']);
    }
}