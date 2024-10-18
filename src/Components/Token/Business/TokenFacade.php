<?php
declare(strict_types=1);

namespace App\Components\Token\Business;

use App\Components\Token\Persistence\Mapper\TokenMapper;
use App\Components\Token\Persistence\TokenEntityManager;
use App\Components\Token\Persistence\TokenRepository;
use App\DataTransferObjects\TokenDTO;

class TokenFacade
{
    public function __construct(
        private TokenRepository    $tokenRepository,
        private TokenEntityManager $tokenEntityManager,
        private TokenMapper        $tokenMapper
    ) {}

    public function saveToken(TokenDTO $tokenDTO): bool
    {
        return $this->tokenEntityManager->save($tokenDTO);
    }

    public function updateToken(TokenDTO $tokenDTO): bool
    {
        return $this->tokenEntityManager->update($tokenDTO);
    }

    public function getTokenByEmail(string $email): ?TokenDTO
    {
        return $this->tokenRepository->getTokenByEmail($email);
    }

    public function createTokenDTO(array $data): ?TokenDTO
    {
        return $this->tokenMapper->createTokenDTO($data);
    }
}