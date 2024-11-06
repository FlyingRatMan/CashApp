<?php
declare(strict_types=1);

namespace App\Components\Account\Persistence\Mapper;

use App\DataTransferObjects\AccountDTO;

class AccountMapper
{
    public function createAccountDTO(array $data): AccountDTO
    {
        return new AccountDTO($data['id'], $data['user_id'], $data['amount'], $data['date']);
    }
}