<?php
declare(strict_types=1);

namespace App\Components\Token\Persistence;

use App\DataTransferObjects\TokenDTO;
use App\Model\DB\ORMEntityManager;

class TokenEntityManager
{
    public function __construct(
        private ORMEntityManager $entityManager
    ) {}

    public function save(TokenDTO $tokenDTO): void // ?TokenEntity
    {
        $entityManager = $this->entityManager::getEntityManager();

        $tokenEntity = new TokenEntity();
        $tokenEntity->setToken($tokenDTO->token);
        $tokenEntity->setEmail($tokenDTO->email);
        $tokenEntity->setExpiresAt($tokenDTO->expires_at);

        $entityManager->persist($tokenEntity);
        $entityManager->flush();
    }

    public function update(TokenDTO $tokenDTO): void
    {
        $entityManager = $this->entityManager::getEntityManager();

        $tokenEntity = $entityManager->find(TokenEntity::class, $tokenDTO->id);

        if ($tokenEntity === null) return;

        $tokenEntity->setToken($tokenDTO->token);
        $tokenEntity->setEmail($tokenDTO->email);
        $tokenEntity->setExpiresAt($tokenDTO->expires_at);

        $entityManager->flush();
    }
}