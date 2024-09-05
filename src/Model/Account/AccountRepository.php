<?php
declare(strict_types=1);

namespace App\Model\Account;

use App\Model\DB\JsonManagerInterface;

readonly class AccountRepository
{
    public function __construct(
        private JsonManagerInterface $jsonManager
    ) {}

    public function findAll(): array
    {
        $data = $this->jsonManager->read();
        $list = [];

        if (empty($data)) {
            return [];
        }

        foreach ($data as $account) {
            $dto = new AccountDTO($account['amount'], $account['date']);

            $list[] = $dto;
        }

        return $list;
    }

    public function getBalance(): int
    {
        $balance = 0;
        $data = $this->jsonManager->read();

        foreach ($data as $transaction) {
            $balance += $transaction['amount'];
        }

        return $balance;
    }
}