<?php
declare(strict_types=1);

namespace App\Model\Account;

use App\Model\DB\JsonManagerInterface;

readonly class AccountEntityManager
{
    // TODO SQLCONNECTOR INSTEAD OF JSONMANAGER
    // todo methods should prepare a query to execute in sqlconnector
    public function __construct(
        private JsonManagerInterface $jsonManager
    ) {}

    public function add(AccountDTO $data): void
    {
        $this->jsonManager->write((array)$data);
    }
}