<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\EntityManager\UserEntityManager;
use App\Model\Repository\UserRepository;
use App\Service\UserValidator;

require __DIR__ . '/../../vendor/autoload.php';

class RegistrationController
{
    public function __construct(
        private $twig,
        private UserEntityManager $userEntityManager,
        private UserRepository $userRepository,
        private UserValidator $userValidator,
    ) {}
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [
                'emailErr' => $this->userValidator->isValidEmail($_POST['email']),
                'passErr' => $this->userValidator->isValidPassword($_POST['password']),
                'userExist' => (bool)$this->userRepository->getUserByEmail($_POST['email']),
            ];

            $_SESSION['userErr'] = $errors;

            if ($errors) {
                $_SESSION['regName'] = $_POST['name'];
                $_SESSION['regEmail'] = $_POST['email'];
                $_SESSION['userErr'] = $errors;
            }

            if ($errors['emailErr'] === '' && $errors['passErr'] === '' && empty($errors['userExist'])) {
                $user = [
                    "name" => $_POST['name'],
                    "email" => $_POST['email'],
                    "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
                ];

                $this->userEntityManager->save($user);

                header("Location: /index.php?page=login");
                exit();
            }
        }

        $twigVars = [
            'userName' => $_SESSION['regName'] ?? null,
            'userEmail' => $_SESSION['regEmail'] ?? null,
            'errors' => $_SESSION['userErr'] ?? null,
        ];

        echo $this->twig->render('register.twig', $twigVars);
    }
}