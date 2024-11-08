<?php
declare(strict_types=1);

namespace Integration\UserResetPassword;

use App\Components\Token\Business\Model\TokenValidation;
use App\Components\User\Business\Model\UserValidation;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserResetPassword\Business\UserResetPasswordFacade;
use App\Components\UserResetPassword\Communication\UserResetPasswordController;
use App\Core\View;
use App\Model\DB\ORMEntityManager;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserResetPasswordControllerTest extends TestCase
{
    private UserRepository $userRepository;
    private UserResetPasswordController $controller;

    protected function setUp(): void
    {
        $loader = new FilesystemLoader('/home/olhapurtova/PhpstormProjects/CashApp/src/View/templates');
        $twig = new Environment($loader);

        $view = new View($twig);
        $sqlConnector = new ORMEntityManager();
        $userMapper = new UserMapper();
        $this->userRepository = new UserRepository($userMapper, $sqlConnector);
        $userEntityManager = new UserEntityManager($sqlConnector);
        $userFacade = new UserBusinessFacade($this->userRepository, $userEntityManager);
        $userValidation = new UserValidation($userFacade);
        $tokenValidation = new TokenValidation($sqlConnector);
        $userResetPasswordFacade = new UserResetPasswordFacade($view, $userFacade, $tokenValidation, $userValidation, $userMapper);
        $this->controller = new UserResetPasswordController($view, $userResetPasswordFacade);
    }

    public function testIndexCallsUpdatePassword(): void
    {
        $_POST['password'] = 'new12QWqw,.';
        $_GET['token'] = bin2hex('update@example.com');

        $this->controller->index();
        $actual = $this->userRepository->getUserByEmail('update@example.com');

        $this->assertTrue(password_verify('new12QWqw,.', $actual->password));
    }
}