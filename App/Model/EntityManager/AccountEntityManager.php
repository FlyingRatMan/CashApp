<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

require __DIR__ . '/../../../vendor/autoload.php';

class AccountEntityManager
{
    public function __construct(
        private JsonManager $jsonManager
    ) {}

    public function add(array $data): void
    {
        $this->jsonManager->write($data);
    }
}