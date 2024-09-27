<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\User\UserEntityManager;
use App\Model\User\UserMapper;
use App\Model\User\UserRepository;
use App\Service\UserValidatorInterface;

readonly class RegistrationController
{
    public function __construct(
        private View                   $view,
        private UserEntityManager      $userEntityManager,
        private UserRepository         $userRepository,
        private UserValidatorInterface $userValidator,
        private UserMapper             $userMapper,
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
                $userDTO = $this->userMapper->createUserDTO([
                    'id' => 0,
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                ]);

                $this->userEntityManager->save($userDTO);

                $this->view->setRedirect('/index.php?page=login');
                return;
            }
        }

        $this->view->setTemplate('register.twig');

        $this->view->addParameter('userName', $_SESSION['regName'] ?? null);
        $this->view->addParameter('userEmail', $_SESSION['regEmail'] ?? null);
        $this->view->addParameter('errors', $_SESSION['userErr'] ?? null);
    }
}