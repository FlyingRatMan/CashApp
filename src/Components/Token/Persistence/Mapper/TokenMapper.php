<?php
declare(strict_types=1);

namespace App\Components\Token\Persistence\Mapper;

use App\DataTransferObjects\TokenDTO;

class TokenMapper
{
    public function createTokenDTO(array $data): TokenDTO
    {
        return new TokenDTO($data['id'], $data['token'], $data['email'], $data['expires_at']);
    }
}