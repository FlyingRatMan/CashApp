<?php
declare(strict_types=1);

namespace App\Model\User;

class UserMapper
{
    // todo having multiple user, need to refactor the function to create an array of userDTOs for each user from the sqlDB

    public function createUserDTO(array $data): UserDTO
    {
        return new UserDTO($data['id'], $data['name'], $data['email'], $data['password']);
    }
}