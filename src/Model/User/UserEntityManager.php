<?php
declare(strict_types=1);

namespace App\Model\User;

use App\Model\DB\SqlConnector;

readonly class UserEntityManager
{
    public function __construct(
        private SqlConnector $sqlConnector,
    ) {}

    public function save(UserDTO $userDTO): void
    {
        $db = $this->sqlConnector::getConnection();
        $query = "INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)";

        $values = [
            ':name' => $userDTO->getName(),
            ':email' => $userDTO->getEmail(),
            ':password' => $userDTO->getPassword(),
        ];

        $db->insert($query, $values);
    }
}