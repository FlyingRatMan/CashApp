<?php
declare(strict_types=1);

namespace App\Service;

use App\Components\UserForgetPassword\Communication\UserForgetPasswordController;
use App\Components\UserLogin\Communication\UserLoginController;
use App\Components\UserRegister\Communication\UserRegisterController;
use App\Controller\HomeController;
use App\Controller\LogoutController;
use App\Controller\ResetPasswordController;

class ControllerProvider
{
    public function getList(): array
    {
        return [
            'home' => HomeController::class,
            'login' => UserLoginController::class,
            'register' => UserRegisterController::class,
            'logout' => LogoutController::class,
            'resetPassword' => ResetPasswordController::class,
            'forgotPassword' => UserForgetPasswordController::class,
        ];
    }
}