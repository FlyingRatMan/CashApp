<?php
declare(strict_types=1);

namespace Unit\Service;

use App\Components\Account\Communication\AccountController;
use App\Components\UserForgetPassword\Communication\UserForgetPasswordController;
use App\Components\UserLogin\Communication\UserLoginController;
use App\Components\UserLogout\Communication\UserLogoutController;
use App\Components\UserRegister\Communication\UserRegisterController;
use App\Components\UserResetPassword\Communication\UserResetPasswordController;
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
            'home' => AccountController::class,
            'login' => UserLoginController::class,
            'register' => UserRegisterController::class,
            'logout' => UserLogoutController::class,
            'resetPassword' => UserResetPasswordController::class,
            'forgotPassword' => UserForgetPasswordController::class,
        ];

        $actual = $this->controllerProvider->getList();

        $this->assertSame($expected, $actual);
    }
}