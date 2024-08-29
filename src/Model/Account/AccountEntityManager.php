<?php
declare(strict_types=1);

namespace App\Model\Account;

use App\Model\DB\JsonManagerInterface;

readonly class AccountEntityManager
{
    public function __construct(
        private JsonManagerInterface $jsonManager
    ) {}

    public function add(array $data): void
    {
        $this->jsonManager->write($data);
    }
}