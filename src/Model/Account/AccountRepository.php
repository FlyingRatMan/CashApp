<?php
declare(strict_types=1);

namespace App\Model\Account;

use App\Model\DB\SqlConnector;

readonly class AccountRepository
{
    public function __construct(
        private SqlConnector $sqlConnector,
    ) {}

    public function findAll(int $userID): array
    {
        $db = $this->sqlConnector::getConnection();
        $query = 'SELECT * FROM Account';
        $transactions = $db->select($query);
        $list = [];

        if (empty($transactions)) {
            return [];
        }
        foreach ($transactions as $transaction) {
            if ($transaction['user_id'] === $userID) {
                $accountDTO = new AccountDTO($transaction['amount'], $transaction['date']);
                $list[] = $accountDTO;
            }
        }

        return $list;
    }

    public function getBalance(int $userID): int
    {
        $balance = 0;
        $transactions = $this->findAll($userID);

        foreach ($transactions as $transaction) {
            $balance += $transaction->getAmount();
        }

        return (int)$balance;
    }
}