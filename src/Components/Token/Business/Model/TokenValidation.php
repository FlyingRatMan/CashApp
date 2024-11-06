<?php
declare(strict_types=1);

namespace App\Components\Token\Business\Model;

use App\Model\DB\SqlConnector;

class TokenValidation
{
    public function __construct(
        private SqlConnector $sqlConnector
    ) {}
    public function validateToken(string $email): bool {
        $db = $this->sqlConnector::getConnection();
        $query = 'SELECT * FROM Reset_password_tokens WHERE email = :email LIMIT 1';
        $data = $db->select($query, ['email' => $email]);

        if ($data) {
            $expiry = $data[0]['expires_at'];
            $now = date('Y-m-d H:i:s');

            if (new \DateTime($expiry) > new \DateTime($now)) {
                return true;
            }
        }

        return false;
    }
}