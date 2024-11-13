<?php
declare(strict_types=1);

namespace App\Components\Token\Persistence;

use App\Components\Token\Persistence\Mapper\TokenMapper;
use App\DataTransferObjects\TokenDTO;
use App\DBConnector\ORMEntityManager;
use App\Entity\TokenEntity;

class TokenRepository
{
    public function __construct(
        private TokenMapper $tokenMapper
    ) {}

    public function getTokenByEmail(string $email): ?TokenDTO
    {
        $repository = ORMEntityManager::getRepository(TokenEntity::class);

        $tokenEntity = $repository->findOneBy(['email' => $email]);

        if ($tokenEntity !== null) {
            return $this->tokenMapper->entityToDTO($tokenEntity);
        }

        return null;
    }
}