<?php
declare(strict_types=1);

namespace App\Model\User;

use App\Model\DB\SqlConnector;

readonly class UserRepository
{
    public function __construct(
        private SqlConnector $sqlConnector,
    ) {}

    /*public function findAll(): array {
        $db = $this->sqlConnector::getConnection();
        $query = 'SELECT * FROM Users';
        return $db->select($query);
    }*/

    public function getUserByEmail(string $email): ?UserDTO
    {
        $db = $this->sqlConnector::getConnection();
        $query = 'SELECT * FROM Users WHERE email = :email LIMIT 1';
        $data = $db->select($query, ['email' => $email]);

        if ($data) {
            $user = $data[0];
            return new UserDTO($user['id'], $user['name'], $user['email'], $user['password']);
        }

        return null;
    }
}