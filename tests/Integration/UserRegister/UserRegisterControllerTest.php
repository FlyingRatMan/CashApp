<?php
declare(strict_types=1);

namespace Integration\UserRegister;

use App\Components\User\Business\Model\UserValidation;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserRegister\Business\UserRegisterFacade;
use App\Components\UserRegister\Communication\UserRegisterController;
use App\Core\View;
use App\DataTransferObjects\UserDTO;
use App\db_script;
use App\Model\DB\ORMEntityManager;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserRegisterControllerTest extends TestCase
{
    private UserRepository $userRepository;
    private UserRegisterController $registrationController;
    private db_script $db_script;

    protected function setUp(): void
    {
        parent::setUp();

        $loader = new FilesystemLoader('/home/olhapurtova/PhpstormProjects/CashApp/src/View/templates');
        $twig = new Environment($loader);

        $view = new View($twig);
        $userMapper = new UserMapper();
        $sqlConnector = new ORMEntityManager();
        $this->userRepository = new UserRepository($userMapper, $sqlConnector);
        $userEntityManager = new UserEntityManager($sqlConnector);
        $userFacade = new UserBusinessFacade($this->userRepository, $userEntityManager);
        $userValidation = new UserValidation($userFacade);
        $userRegisterFacade = new UserRegisterFacade($view, $userValidation, $userMapper, $userEntityManager);
        $this->registrationController = new UserRegisterController($view, $userRegisterFacade, $userMapper);

        $this->db_script = new db_script();
        $this->db_script->prefillDatabase();
    }

    protected function tearDown(): void
    {
        $this->db_script->cleanDatabase();

        parent::tearDown();
    }

    public function testIndexRegisterValid(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['name'] = 'Test';
        $_POST['email'] = 'testRegister@calls.register.facade';
        $_POST['password'] = '12QWqw,.';

        $this->registrationController->index();
        $actual = $this->userRepository->getUserByEmail('testRegister@calls.register.facade');

        $this->assertInstanceOf(UserDTO::class, $actual);
        $this->assertSame('Test', $actual->name);
        $this->assertSame('testRegister@calls.register.facade', $actual->email);
    }
}