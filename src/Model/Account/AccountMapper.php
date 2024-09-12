<?php
declare(strict_types=1);

namespace App\Model\Account;

class AccountMapper
{
    // TODO EACH USER HAS MULTIPLE TRANSACTIONS, REFACTOR THE FUNCTION TO MAKE AN ARRAY OF ACCOUNTDTOS
    public function createAccountDTO(array $data): AccountDTO
    {
        return new AccountDTO($data['amount'], $data['date']);
    }
}