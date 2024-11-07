<?php
declare(strict_types=1);

namespace App\Components\Account\Persistence;

use App\DataTransferObjects\AccountDTO;
use App\Model\DB\SqlConnector;

class AccountEntityManager
{
    public function __construct(
        private SqlConnector $sqlConnector,
    ) {}

    public function add(AccountDTO $accountDTO, $userID): void
    {
        $db = $this->sqlConnector::getConnection();
        $query = "INSERT INTO Account (user_id, amount, date) 
            VALUES (:user_id, :amount, :date)";

        $params = [
            'user_id' => $userID,
            'amount' => $accountDTO->amount,
            'date' => $accountDTO->date,
        ];

        $db->insert($query, $params);
    }
}