<?php
declare(strict_types=1);

namespace App\Components\UserLogin\Business;

use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\UserDTO;

class UserLoginFacade
{
    public function __construct(
        private UserBusinessFacade $userFacade,
    ) {}

    public function getUserByEmail(string $email): ?UserDTO
    {
        return $this->userFacade->getUserByEmail($email);
    }
}