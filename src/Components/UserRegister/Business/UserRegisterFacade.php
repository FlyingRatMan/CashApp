<?php
declare(strict_types=1);

namespace App\Components\UserRegister\Business;

use App\Components\User\Business\Model\UserValidation;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Core\View;
use App\DataTransferObjects\UserDTO;
use Error;

class UserRegisterFacade
{
    public function __construct(
        private View              $view,
        private UserValidation    $validation,
        private UserMapper        $userMapper,
        private UserEntityManager $entityManager,
    ) {}

    public function register(UserDTO $userDTO): void
    {
        $error = $this->validation->validateCredentials($userDTO->email, $userDTO->password);

        if ($error === null) {
            $userDTO = $this->userMapper->createUserDTO([
                'id' => 1,
                'name' => $userDTO->name,
                'email' => $userDTO->email,
                'password' => password_hash($userDTO->password, PASSWORD_DEFAULT),
            ]);

            $this->entityManager->save($userDTO);

            $this->view->setRedirect('/index.php?page=login');
            return;
        }

        $this->view->addParameter('userName', $userDTO->name);
        $this->view->addParameter('userEmail', $userDTO->email);
        $this->view->addParameter('errors', $error);
    }
}