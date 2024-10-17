<?php
declare(strict_types=1);

namespace App\Components\User\Persistence;

use App\Components\User\UserDTO;
use App\Model\DB\SqlConnector;

class UserEntityManager
{
    public function __construct(
        private SqlConnector $sqlConnector,
    ) {}

    public function save(UserDTO $userDTO): void
    {
        $db = $this->sqlConnector::getConnection();
        $query = "INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)";

        $params = [
            ':name' => $userDTO->name,
            ':email' => $userDTO->email,
            ':password' => $userDTO->password,
        ];

        $db->insert($query, $params);
    }

    public function updatePassword(UserDTO $userDTO, string $password): void
    {
        $db = $this->sqlConnector::getConnection();
        $query = "UPDATE Users SET name = :name, email = :email, password = :password WHERE id = :id";

        $params = [
            ':name' => $userDTO->name,
            ':email' => $userDTO->email,
            ':password' => $password,
            ':id' => $userDTO->id,
        ];

        $db->update($query, $params);
    }
}