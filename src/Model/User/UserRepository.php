<?php
declare(strict_types=1);

namespace App\Model\User;

use App\Model\DB\JsonManagerInterface;

readonly class UserRepository
{
    public function __construct(
        private JsonManagerInterface $jsonManager
    ) {}

    public function getUserByEmail(string $email): array
    {
        $users = $this->jsonManager->read();

        foreach ($users as $user) {
            if ($user['email'] === $email) {
                // TODO should it return dto?
                return $user;
            }
        }

        return [];
    }
}