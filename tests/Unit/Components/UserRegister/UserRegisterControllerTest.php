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
use App\Components\UserRegister\Communication\UserRegisterController;
use App\Core\View;
use App\DataTransferObjects\UserDTO;
use App\Model\DB\SqlConnector;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserRegisterControllerTest extends TestCase
{
    /*private View $view;
    private UserMapper $userMapper;
    private UserRegisterFacade $userRegisterFacade;
    private UserRegisterController $registrationController;

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
        $this->registrationController = new UserRegisterController($this->view, $this->userRegisterFacade, $this->userMapper);
    }

    public function testIndexSetsTemplate(): void
    {
        $_SERVER['REQUEST_METHOD'] = false;

        $this->registrationController->index();

        $template = $this->view->getTemplate();

        $this->assertSame('register.twig', $template);
    }

    public function testIndexRegisterValid(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $user = $this->userMapper->createUserDTO(['id' => 1, 'name' => 'user', 'email' => 'email@test.com', 'password' => password_hash('12QWqw,.', PASSWORD_DEFAULT)]);

        $this->registrationController->index();


        //$this->assertSame('');
    }*/

    private UserRegisterFacade $userRegisterFacade;
    private UserMapper $userMapper;
    private View $view;
    private UserRegisterController $controller;

    protected function setUp(): void
    {
        $this->userRegisterFacade = $this->createMock(UserRegisterFacade::class);
        $this->userMapper = $this->createMock(UserMapper::class);
        $this->view = $this->createMock(View::class);

        $this->controller = new UserRegisterController(
            $this->view,
            $this->userRegisterFacade,
            $this->userMapper
        );
    }

    public function testIndexCallsRegisterOnPost(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['name'] = 'Test User';
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'password123';

        $newUser = [
            'id' => 1,
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
        ];

        $userDTO = new UserDTO(1, 'Test User', 'test@example.com', 'password123');

        $this->userMapper
            ->expects($this->once())
            ->method('createUserDTO')
            ->with($newUser)
            ->willReturn($userDTO);

        $this->userRegisterFacade
            ->expects($this->once())
            ->method('register')
            ->with($userDTO);

        $this->controller->index();
    }

    public function testViewTemplateIsSet(): void
    {
        $this->view
            ->expects($this->once())
            ->method('setTemplate')
            ->with('register.twig');

        $this->controller->index();
    }
}