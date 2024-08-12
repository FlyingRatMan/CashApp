<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\EntityManager\JsonManager;

require __DIR__ . '/../../../vendor/autoload.php';

class AccountRepository
{
    public function __construct(
        private JsonManager $jsonManager
    ) {}

    public function findAll(): array
    {
        return $this->jsonManager->read();
    }
}