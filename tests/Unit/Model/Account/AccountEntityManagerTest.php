<?php
declare(strict_types=1);

namespace Unit\Model\Account;

use App\Model\Account\AccountEntityManager;
use App\Model\DB\JsonManager;
use PHPUnit\Framework\TestCase;

class AccountEntityManagerTest extends TestCase
{
    public function testAdd(): void
    {
        $data = [
            'amount' => 100,
            'date' => '2024-08-28'
        ];

        $jsonManagerMock = $this->createMock(JsonManager::class);

        $jsonManagerMock
            ->expects($this->once())
            ->method('write')
            ->with($data);

        $accountEntityManager = new AccountEntityManager($jsonManagerMock);

        $accountEntityManager->add($data);
    }
}
