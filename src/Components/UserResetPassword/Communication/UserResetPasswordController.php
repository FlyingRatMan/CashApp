<?php
declare(strict_types=1);

namespace App\Components\UserResetPassword\Communication;

use App\Components\UserResetPassword\Business\UserResetPasswordFacade;
use App\Core\View;

class UserResetPasswordController
{
    public function __construct(
        private View $view,
        private UserResetPasswordFacade $userResetPasswordFacade
    ) {}

    public function index(): void
    {

    }
}