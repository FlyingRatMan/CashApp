<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\User\UserRepository;
use App\Service\UserValidator;
use Twig\Environment;

readonly class LoginController
{
    public function __construct(
        private Environment $twig,
        private UserRepository $userRepository,
        private UserValidator $userValidator,
    ) {}

    public function index(): void
    {
        $err = $_SESSION['loginErr'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->userRepository->getUserByEmail($_POST['email']);

            if ($user !== []) {
                $validUser = $this->userValidator->isValidCredentials($_POST['password'], $user);

                if ($validUser) {
                    $_SESSION['loggedUser'] = $user['name'];

                    header("Location: /");
                    exit();
                }
            }

            $_SESSION['loginErr'] = 'Wrong user credentials.';
        }

        echo $this->twig->render('login.twig', ['err' => $err]);
    }
}