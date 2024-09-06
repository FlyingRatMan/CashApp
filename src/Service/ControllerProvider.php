<?php
declare(strict_types=1);

namespace App\Service;

use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\RegistrationController;

class ControllerProvider
{
    public function getList(): array
    {
        return [
            'home' => HomeController::class,
            'login' => LoginController::class,
            'register' => RegistrationController::class,
            'logout' => LogoutController::class,
        ];
    }
}