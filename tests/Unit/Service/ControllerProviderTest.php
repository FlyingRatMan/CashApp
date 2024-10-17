<?php
declare(strict_types=1);

namespace Unit\Service;

use App\Controller\ForgotPasswordController;
use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\RegistrationController;
use App\Controller\ResetPasswordController;
use App\Service\ControllerProvider;
use PHPUnit\Framework\TestCase;

class ControllerProviderTest extends TestCase
{
    private ControllerProvider $controllerProvider;

    protected function setUp(): void
    {
        $this->controllerProvider = new ControllerProvider();
    }

    public function testGetListReturnsCorrectControllers(): void
    {
        $expected = [
            'home' => HomeController::class,
            'login' => LoginController::class,
            'register' => RegistrationController::class,
            'logout' => LogoutController::class,
            'resetPassword' => ResetPasswordController::class,
            'forgotPassword' => ForgotPasswordController::class,
        ];

        $actual = $this->controllerProvider->getList();

        $this->assertSame($expected, $actual);
    }
}