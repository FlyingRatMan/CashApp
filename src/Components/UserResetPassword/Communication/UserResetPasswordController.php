<?php
declare(strict_types=1);

namespace App\Components\UserResetPassword\Communication;

use App\Components\UserResetPassword\Business\UserResetPasswordFacade;
use App\Core\View;

class UserResetPasswordController
{
    public function __construct(
        private View                    $view,
        private UserResetPasswordFacade $userResetPasswordFacade
    ) {}

    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userResetPasswordFacade->resetPassword($_GET['token'], $_POST['password']);
        }

        $this->view->setTemplate('resetPassword.twig');
    }
}