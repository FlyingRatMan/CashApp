<?php
declare(strict_types=1);

namespace App\Components\Account\Business\Model;

use App\Components\Account\Persistence\AccountRepository;

class AccountService
{
    public function __construct(
        private AccountRepository    $accountRepository,
    ) {}

    public function getBalance(int $userID): int
    {
        $balance = 0;
        $transactions = $this->accountRepository->findAll($userID);

        foreach ($transactions as $transaction) {
            $balance += $transaction->amount;
        }

        return (int)$balance;
    }
}