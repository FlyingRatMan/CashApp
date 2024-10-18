<?php
declare(strict_types=1);

namespace App\Components\Token\Persistence;

use App\DataTransferObjects\TokenDTO;
use App\Model\DB\SqlConnector;

class TokenEntityManager
{
    public function __construct(
        private SqlConnector    $sqlConnector
    ) {}

    public function save(TokenDTO $tokenDTO): bool
    {
        $db = $this->sqlConnector::getConnection();

        $insertQuery = "INSERT INTO Reset_password_tokens (token, email, expires_at) 
                            VALUES (:token, :email, :expires_at)";
        $params = [
            'token' => $tokenDTO->token,
            'email' => $tokenDTO->email,
            'expires_at' => $tokenDTO->expires_at,
        ];

        return $db->insert($insertQuery, $params);
    }

    public function update(TokenDTO $tokenDTO): bool
    {
        $db = $this->sqlConnector::getConnection();
        $query = "UPDATE Reset_password_tokens SET token = :token, email = :email, expires_at = :expires_at
                             WHERE id = :id";
        $params = [
            'id' => $tokenDTO->id,
            'token' => $tokenDTO->token,
            'email' => $tokenDTO->email,
            'expires_at' => $tokenDTO->expires_at,
        ];

        return $db->update($query, $params);
    }
}