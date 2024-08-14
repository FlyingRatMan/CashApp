<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Repository\UserRepository;

require __DIR__ . '/../../../vendor/autoload.php';

class UserEntityManager
{
    public function __construct(
        private JsonManager $jsonManager
    ) {}

    public function save(array $user): void
    {
        $this->jsonManager->write($user);
    }
}