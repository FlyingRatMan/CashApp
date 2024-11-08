<?php
declare(strict_types=1);

namespace App\Components\User\Persistence;

use App\DataTransferObjects\UserDTO;
use App\Model\DB\ORMEntityManager;

class UserEntityManager
{
    public function __construct(
        private ORMEntityManager $ORMEntityManager,
    ) {}

    public function save(UserDTO $userDTO): void // ?UserEntity
    {
        $entityManager = $this->ORMEntityManager::getEntityManager();

        $userEntity = new UserEntity();
        $userEntity->setName($userDTO->name);
        $userEntity->setEmail($userDTO->email);
        $userEntity->setPassword($userDTO->password);

        $entityManager->persist($userEntity);
        $entityManager->flush();
    }

    public function updatePassword(UserDTO $userDTO, string $password): void
    {
        $entityManager = $this->ORMEntityManager::getEntityManager();

        $userEntity = $entityManager->find(UserEntity::class, $userDTO->id);

        if ($userEntity === null) return;

        $userEntity->setName($userDTO->name);
        $userEntity->setEmail($userDTO->email);
        $userEntity->setPassword(password_hash($password, PASSWORD_DEFAULT));

        $entityManager->flush();
    }
}