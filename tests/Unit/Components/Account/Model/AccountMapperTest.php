<?php
declare(strict_types=1);

namespace Unit\Components\Account\Model;

use App\Components\Account\Persistence\Mapper\AccountMapper;
use PHPUnit\Framework\TestCase;

class AccountMapperTest extends TestCase
{
    public function testCreateAccountDTOReturnsAccountDTO(): void
    {
        $mapper = new AccountMapper();
        $expectedTransaction = [
            'id' => 1,
            'user_id' => 1,
            'amount' => 10.0,
            'date' => '2024-08-22 10:29:56',
        ];

        $actualAccountDTO = $mapper->createAccountDTO($expectedTransaction);

        $this->assertSame($expectedTransaction['amount'], $actualAccountDTO->amount);
        $this->assertSame($expectedTransaction['date'], $actualAccountDTO->date);
    }
}