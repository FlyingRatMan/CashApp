<?php
declare(strict_types=1);

namespace App\Service;

use App\Components\Account\Communication\AccountController;
use App\Components\UserForgetPassword\Communication\UserForgetPasswordController;
use App\Components\UserLogin\Communication\UserLoginController;
use App\Components\UserRegister\Communication\UserRegisterController;
use App\Components\UserResetPassword\Communication\UserResetPasswordController;
use App\Controller\LogoutController;

class ControllerProvider
{
    public function getList(): array
    {
        return [
            'home' => AccountController::class,
            'login' => UserLoginController::class,
            'register' => UserRegisterController::class,
            'logout' => LogoutController::class,
            'resetPassword' => UserResetPasswordController::class,
            'forgotPassword' => UserForgetPasswordController::class,
        ];
    }
}