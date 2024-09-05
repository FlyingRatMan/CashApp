<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\User\UserRepository;
use App\Service\UserValidatorInterface;

readonly class LoginController
{
    public function __construct(
        private View $view,
        private UserRepository $userRepository,
        private UserValidatorInterface $userValidator,
    ) {}

    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->userRepository->getUserByEmail($_POST['email']);

            if (!empty($user)) {
                $validUser = $this->userValidator->isValidCredentials($_POST['password'], $user);

                if ($validUser) {
                    $_SESSION['loggedUser'] = $user['name'];

                    header("Location: /");
                    exit();
                }
            }

            $err = 'Wrong user credentials.';
        }

        $this->view->setTemplate('login.twig');

        $this->view->addParameter('err', $err);
    }
}