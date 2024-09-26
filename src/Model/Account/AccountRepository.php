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
        $query = 'SELECT * FROM Account WHERE user_id = :userID';
        $transactions = $db->select($query, ['userID' => $userID]);

        if (empty($transactions)) {
            return [];
        }

        $list = [];
        foreach ($transactions as $transaction) {
            $accountDTO = new AccountDTO($transaction['id'], $transaction['user_id'], $transaction['amount'], $transaction['date']);
            $list[] = $accountDTO;
        }

        return $list;
    }
}