<?php
declare(strict_types=1);

namespace App\Components\User\Business;

use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\DataTransferObjects\UserDTO;

class UserBusinessFacade
{
    public function __construct(
        private UserRepository    $userRepository,
        private UserEntityManager $userEntityManager
    ) {}

    public function getUserByEmail(string $email): ?UserDTO
    {
        return $this->userRepository->getUserByEmail($email);
    }

    public function save(UserDTO $userDTO): void
    {
        $this->userEntityManager->save($userDTO);
    }

    public function updatePassword(UserDTO $userDTO, string $newPassword): void
    {
        $this->userEntityManager->updatePassword($userDTO, $newPassword);
    }
}