<?php
declare(strict_types=1);

namespace App\Components\UserRegister\Communication;

use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\UserRegister\Business\UserRegisterFacade;
use App\Core\View;

class UserRegisterController
{
    public function __construct(
        private View               $view,
        private UserRegisterFacade $userRegisterFacade,
        private UserMapper         $userMapper,
    ) {}

    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newUser = [
                'id' => 1,
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
            ];

            $userDTO = $this->userMapper->createUserDTO($newUser);

            $this->userRegisterFacade->register($userDTO);
        }

        $this->view->setTemplate('register.twig');
    }
}