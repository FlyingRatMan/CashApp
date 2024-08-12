<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\EntityManager\JsonManager;
use Error;

require __DIR__ . '/../../../vendor/autoload.php';

class UserRepository
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

        throw new Error('User does not exist');
    }

    public function print(): void
    {
        echo "USER REPO YAY";
    }
}