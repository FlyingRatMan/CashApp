<?php
declare(strict_types=1);

namespace App\Components\UserForgetPassword\Communication;

use App\Components\UserForgetPassword\Business\UserForgetPasswordFacade;
use App\Core\View;

class UserForgetPasswordController
{
    public function __construct(
        private View                     $view,
        private UserForgetPasswordFacade $userForgetPasswordFacade
    ) {}

    public function index()
    {
        if (isset($_POST['sendEmail'])) {
            $this->userForgetPasswordFacade->sendEmail($_POST['email']);
        }

        $this->view->setTemplate('forgotPassword.twig');
    }
}