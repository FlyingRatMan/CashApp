<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\Mapper;

use App\DataTransferObjects\UserDTO;

class UserMapper
{
    public function createUserDTO(array $data): UserDTO
    {
        return new UserDTO($data['id'], $data['name'], $data['email'], $data['password']);
    }
}