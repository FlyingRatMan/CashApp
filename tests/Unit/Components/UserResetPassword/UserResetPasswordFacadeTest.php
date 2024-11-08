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
use App\Core\View;
use App\Model\DB\ORMEntityManager;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserResetPasswordFacadeTest extends TestCase
{
    private View $view;
    private UserResetPasswordFacade $facade;

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
        $this->facade = new UserResetPasswordFacade($this->view, $userFacade, $tokenValidation, $userValidation, $userMapper);
    }

    public function testResetPasswordWithInvalidNewPassword(): void
    {
        $newPassword = 'invalid';
        $token = bin2hex('update@example.com');

        $this->facade->resetPassword($token, $newPassword);
        $params = $this->view->getParameters();

        $this->assertInstanceOf(\Error::class, $params['error']);
        $this->assertSame('Password should be at least 6 characters long, and have special characters, numbers, 
                    capital and lower case letters.', $params['error']->getMessage());
    }

    public function testResetPasswordRedirectsOnInvalidToken(): void
    {
        $token = bin2hex('max@example.com');

        $this->facade->resetPassword($token, '12QWqw,.');
        $redirect = $this->view->getRedirectTo();

        $this->assertSame('Location: /index.php?page=forgotPassword', $redirect);
    }
}