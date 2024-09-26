<?php
declare(strict_types=1);

namespace App\Model\User;

class UserMapper
{
    public function createUserDTO(array $data): UserDTO
    {
        return new UserDTO($data['id'], $data['name'], $data['email'], $data['password']);
    }
}