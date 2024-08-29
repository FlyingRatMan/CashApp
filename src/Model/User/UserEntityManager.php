<?php
declare(strict_types=1);

namespace App\Model\User;

use App\Model\DB\JsonManagerInterface;

readonly class UserEntityManager
{
    public function __construct(
        private JsonManagerInterface $jsonManager
    ) {}

    public function save(array $user): void
    {
        $this->jsonManager->write($user);
    }
}