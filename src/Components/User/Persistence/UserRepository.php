<?php
declare(strict_types=1);

namespace App\Components\User\Persistence;

use App\Components\User\Persistence\Mapper\UserMapper;
use App\DataTransferObjects\UserDTO;
use App\DBConnector\ORMEntityManager;
use App\Entity\UserEntity;

class UserRepository
{
    public function __construct(
        private UserMapper $userMapper
    ) {}

    public function getUserByEmail(string $email): ?UserDTO
    {
        $repository = ORMEntityManager::getRepository(UserEntity::class);

        $userEntity = $repository->findOneBy(['email' => $email]);

        if ($userEntity !== null) {
            return $this->userMapper->entityToDTO($userEntity);
        }

        return null;
    }
}