<?php
declare(strict_types=1);

namespace App\Components\Token\Persistence;

use App\Components\Token\Persistence\Mapper\TokenMapper;
use App\DataTransferObjects\TokenDTO;
use App\Model\DB\SqlConnector;

class TokenRepository
{
    public function __construct(
        private SqlConnector $sqlConnector,
        private TokenMapper  $tokenMapper
    ) {}

    public function getTokenByEmail(string $email): ?TokenDTO
    {
        $db = $this->sqlConnector::getConnection();
        $query = 'SELECT * FROM Reset_password_tokens WHERE email = :email LIMIT 1';

        $data = $db->select($query, ['email' => $email]);

        if (!empty($data)) {
            $token = $data[0];
            return $this->tokenMapper->createTokenDTO($token);
        }

        return null;
    }
}