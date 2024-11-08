<?php
declare(strict_types=1);

namespace App\Components\Account\Persistence;

use App\Components\Account\Persistence\Mapper\AccountMapper;
use App\Model\DB\ORMEntityManager;

class AccountRepository
{
    public function __construct(
        private ORMEntityManager $sqlConnector,
        private AccountMapper    $accountMapper
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
            $accountDTO = $this->accountMapper->createAccountDTO(
                [
                    'id' => $transaction['id'],
                    'user_id' => $transaction['user_id'],
                    'amount' => $transaction['amount'],
                    'date' => $transaction['date']]);
            $list[] = $accountDTO;
        }

        return $list;
    }
}