<?php
declare(strict_types=1);

namespace App\Components\User\Persistence;

use App\Components\User\UserDTO;
use App\Model\DB\SqlConnector;

class UserRepository
{
    public function __construct(
        private UserMapper  $userMapper,
        private SqlConnector $sqlConnector,
    ) {}

    public function getUserByEmail(string $email): ?UserDTO
    {
        $db = $this->sqlConnector::getConnection();
        $query = 'SELECT * FROM Users WHERE email = :email LIMIT 1';
        $data = $db->select($query, ['email' => $email]);

        if ($data) {
            $user = $data[0];
            return $this->userMapper->createUserDTO($user);
        }

        return null;
    }
}