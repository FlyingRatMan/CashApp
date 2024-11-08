<?php
declare(strict_types=1);

namespace Unit\Components\UserRegister;

use App\Components\User\Business\Model\UserValidation;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserRegister\Business\UserRegisterFacade;
use App\Components\UserRegister\Communication\UserRegisterController;
use App\Core\View;
use App\Model\DB\ORMEntityManager;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserRegisterControllerTest extends TestCase
{
    private View $view;
    private UserRegisterController $registrationController;

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
        $userRegisterFacade = new UserRegisterFacade($this->view, $userValidation, $userMapper, $userEntityManager);
        $this->registrationController = new UserRegisterController($this->view, $userRegisterFacade, $userMapper);
    }

    public function testIndexSetsTemplate(): void
    {
        $_SERVER['REQUEST_METHOD'] = false;

        $this->registrationController->index();

        $template = $this->view->getTemplate();

        $this->assertSame('register.twig', $template);
    }
}