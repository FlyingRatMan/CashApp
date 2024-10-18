<?php
declare(strict_types=1);

namespace App\Components\UserLogin\Communication;

use App\Components\UserLogin\Business\UserLoginFacade;
use App\Core\View;

class UserLoginController
{
    public function __construct(
        private View            $view,
        private UserLoginFacade $userLoginFacade,
    ) {}

    public function index(): void
    {
        if (isset($_POST['login'])) {
            $this->userLoginFacade->login($_POST['email'], $_POST['password']);
        }

        if (isset($_POST['reset'])) {
            $this->view->setRedirect('/index.php?page=forgotPassword');
            return;
        }

        $this->view->setTemplate('login.twig');
    }
}