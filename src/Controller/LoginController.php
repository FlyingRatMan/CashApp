<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\User\UserRepository;
use App\Service\UserValidatorInterface;

readonly class LoginController
{
    public function __construct(
        private View                   $view,
        private UserRepository         $userRepository,
        private UserValidatorInterface $userValidator,
    ) {}

    public function index(): void
    {
        if (isset($_POST['reset'])) {
            $this->view->setRedirect('/index.php?page=forgotPassword');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->userRepository->getUserByEmail($_POST['email']);

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
        }

        $this->view->setTemplate('login.twig');

        $this->view->addParameter('err', $err);
    }
}