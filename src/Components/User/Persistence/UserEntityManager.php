<?php
declare(strict_types=1);

namespace App\Components\User\Persistence;

use App\DataTransferObjects\UserDTO;
use App\Model\DB\ORMEntityManager;

class UserEntityManager
{
    public function __construct(
        private ORMEntityManager $sqlConnector,
    ) {}

    public function save(UserDTO $userDTO): bool
    {
        $db = $this->sqlConnector::getConnection();
        $query = "INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)";

        $params = [
            ':name' => $userDTO->name,
            ':email' => $userDTO->email,
            ':password' => $userDTO->password,
        ];

        return $db->insert($query, $params);
    }

    public function updatePassword(UserDTO $userDTO, string $password): bool
    {
        $db = $this->sqlConnector::getConnection();
        $query = "UPDATE Users SET name = :name, email = :email, password = :password WHERE id = :id";

        $params = [
            ':name' => $userDTO->name,
            ':email' => $userDTO->email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':id' => $userDTO->id,
        ];

        return $db->update($query, $params);
    }
}