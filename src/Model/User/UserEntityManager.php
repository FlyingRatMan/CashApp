<?php
declare(strict_types=1);

namespace App\Model\User;

use App\Model\DB\JsonManager;

readonly class UserEntityManager
{
    public function __construct(
        private JsonManager $jsonManager
    ) {}

    public function save(array $user): void
    {
        $this->jsonManager->write($user);
    }
}