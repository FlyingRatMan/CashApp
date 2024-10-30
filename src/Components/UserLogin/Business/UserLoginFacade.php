<?php
declare(strict_types=1);

namespace App\Components\UserLogin\Business;

use App\Components\User\Business\Model\UserValidation;
use App\Core\View;

class UserLoginFacade
{
    public function __construct(
        private View           $view,
        private UserValidation $userValidation
    ) {}

    public function login(string $email, string $password): void
    {
        $user = $this->userValidation->validateUser($email, $password);

        if ($user !== null) {
            $_SESSION['loggedUser'] = $user->name;
            $_SESSION['loggedUserId'] = $user->id;

            $this->view->setRedirect('/');
            return;
        }

        $this->view->addParameter('err', 'Wrong user credentials.');
    }
}