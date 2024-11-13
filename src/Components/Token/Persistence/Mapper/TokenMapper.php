<?php
declare(strict_types=1);

namespace App\Components\Token\Persistence\Mapper;

use App\DataTransferObjects\TokenDTO;
use App\Entity\TokenEntity;

class TokenMapper
{
    public function createTokenDTO(array $data): TokenDTO
    {
        return new TokenDTO($data['id'], $data['token'], $data['email'], $data['expires_at']);
    }

    public function entityToDTO(TokenEntity $entity): TokenDTO
    {
        $id = $entity->getId();
        $token = $entity->getToken();
        $email = $entity->getEmail();
        $expiresAt = $entity->getExpiresAt();

        return new TokenDTO(id: $id, token: $token, email: $email, expires_at: $expiresAt);
    }
}