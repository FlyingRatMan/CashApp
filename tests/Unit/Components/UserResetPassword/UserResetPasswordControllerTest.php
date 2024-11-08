<?php
declare(strict_types=1);

namespace Unit\Components\UserResetPassword;

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
    private View $view;
    private UserResetPasswordController $controller;

    protected function setUp(): void
    {
        $loader = new FilesystemLoader('/home/olhapurtova/PhpstormProjects/CashApp/src/View/templates');
        $twig = new Environment($loader);

        $this->view = new View($twig);
        $sqlConnector = new ORMEntityManager();
        $userMapper = new UserMapper();
        $userRepository = new UserRepository($userMapper, $sqlConnector);
        $userManager = new UserEntityManager($sqlConnector);
        $userFacade = new UserBusinessFacade($userRepository, $userManager);
        $tokenValidation = new TokenValidation($sqlConnector);
        $userValidation = new UserValidation($userFacade);
        $userResetPasswordFacade = new UserResetPasswordFacade($this->view, $userFacade, $tokenValidation, $userValidation, $userMapper);
        $this->controller = new UserResetPasswordController($this->view, $userResetPasswordFacade);
    }

    public function testIndexSetsTemplate(): void
    {
        $_SERVER['REQUEST_METHOD'] = false;

        $this->controller->index();
        $template = $this->view->getTemplate();

        $this->assertSame('resetPassword.twig', $template);
    }
}