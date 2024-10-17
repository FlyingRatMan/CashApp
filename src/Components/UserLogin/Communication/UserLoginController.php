<?php
declare(strict_types=1);

namespace App\Components\UserLogin\Communication;

use App\Components\User\Business\UserBusinessFacade;
use App\Core\View;
use App\Service\UserValidatorInterface;

class UserLoginController
{
    public function __construct(
        private View                   $view,
        private UserBusinessFacade     $userFacade,
        private UserValidatorInterface $userValidator,
    ) {}

    public function index(): void
    {
        if (isset($_POST['login'])) {
            $user = $this->userFacade->getUserByEmail($_POST['email']);

            if (!empty($user)) {
                $validUser = $this->userValidator->isValidCredentials($_POST['password'], $user->password);

                if ($validUser) {
                    $_SESSION['loggedUser'] = $user->name;
                    $_SESSION['loggedUserId'] = $user->id;

                    $this->view->setRedirect('/');
                    return;
                }
            }

            $err = 'Wrong user credentials.';

            $this->view->addParameter('err', $err);
        }

        if (isset($_POST['reset'])) {
            $this->view->setRedirect('/index.php?page=forgotPassword');
            return;
        }

        $this->view->setTemplate('login.twig');
    }
}