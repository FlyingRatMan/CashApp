<?php
declare(strict_types=1);

namespace src\Model\Repository;

use Error;
use src\Model\EntityManager\JsonManager;

class UserRepository
{
    public function __construct(
        private JsonManager $dbManager
    ) {}

    public function getUserByEmail(string $email): array
    {
        $users = $this->dbManager->readJson();

        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }

        throw new Error('User does not exist');
    }
}