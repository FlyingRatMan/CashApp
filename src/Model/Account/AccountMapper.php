<?php
declare(strict_types=1);

namespace App\Model\Account;

class AccountMapper
{
    public function createAccountDTO(array $data): AccountDTO
    {
        return new AccountDTO($data['amount'], $data['date']);
    }
}