<?php
declare(strict_types=1);

namespace App\Model\User;

use App\Model\DB\JsonManager;

readonly class UserRepository
{
    public function __construct(
        private JsonManager $jsonManager
    ) {}

    public function getUserByEmail(string $email): array
    {
        $users = $this->jsonManager->read();

        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }

        return [];
    }
}