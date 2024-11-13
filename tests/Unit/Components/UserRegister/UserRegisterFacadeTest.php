<?php
declare(strict_types=1);

namespace Unit\Components\UserRegister;

use App\Components\User\Business\Model\UserValidation;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserRegister\Business\UserRegisterFacade;
use App\Core\View;
use App\db_script;
use App\DBConnector\ORMEntityManager;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserRegisterFacadeTest extends TestCase
{
    private UserRepository $userRepository;
    private UserRegisterFacade $userRegisterFacade;
    private UserMapper $userMapper;
    private View $view;
    private db_script $db_script;

    protected function setUp(): void
    {
        parent::setUp();

        $loader = new FilesystemLoader('/home/olhapurtova/PhpstormProjects/CashApp/src/View/templates');
        $twig = new Environment($loader);

        $this->view = new View($twig);
        $this->userMapper = new UserMapper();
        $this->userRepository = new UserRepository($this->userMapper);
        $userEntityManager = new UserEntityManager(new ORMEntityManager());
        $userFacade = new UserBusinessFacade($this->userRepository, $userEntityManager);
        $userValidation = new UserValidation($userFacade);
        $this->userRegisterFacade = new UserRegisterFacade($this->view, $userValidation, $this->userMapper, $userEntityManager);

        $this->db_script = new db_script();
        $this->db_script->prefillDatabase();
    }

    protected function tearDown(): void
    {
        $this->db_script->cleanDatabase();

        parent::tearDown();
    }

    public function testRegisterValidUser(): void
    {
        $user = $this->userMapper->createUserDTO(['id' => 1, 'name' => 'Test', 'email' => 'testRegister@valid.user', 'password' => password_hash('12QWqw,.', PASSWORD_DEFAULT)]);

        $this->userRegisterFacade->register($user);

        $actualUser = $this->userRepository->getUserByEmail($user->email);
        $param = $this->view->getParameters();

        $this->assertSame($user->name, $actualUser->name);
        $this->assertEmpty($param);
    }

    public function testRegisterInvalidUser(): void
    {
        $user = $this->userMapper->createUserDTO(['id' => 1, 'name' => 'Test', 'email' => 'testRegister@invalid.user', 'password' => 'invalid']);

        $this->userRegisterFacade->register($user);

        $param = $this->view->getParameters();

        $this->assertSame('Test', $param['userName']);
        $this->assertSame('testRegister@invalid.user', $param['userEmail']);
    }
}