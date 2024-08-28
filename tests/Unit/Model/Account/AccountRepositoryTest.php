<?php
declare(strict_types=1);

namespace Unit\Model\Account;

use App\Model\Account\AccountRepository;
use App\Model\DB\JsonManager;
use PHPUnit\Framework\TestCase;

class AccountRepositoryTest extends TestCase
{
    public function testFindAllReturnsEmpty(): void
    {
        $jsonManagerMock = $this->createMock(JsonManager::class);

        $jsonManagerMock
            ->expects($this->once())
            ->method('read')
            ->willReturn([]);

        $accountRepository = new AccountRepository($jsonManagerMock);

        $actualData = $accountRepository->findAll();

        $this->assertEmpty($actualData);
    }

    public function testFindAllReturnsTransactions(): void
    {
        $expectedData = [
            ['amount' => 22, 'date' => '2024-08-22 10:25:16'],
            ['amount' => 22, 'date' => '2024-08-22 10:25:16']
        ];

        $jsonManagerMock = $this->createMock(JsonManager::class);

        $jsonManagerMock
            ->expects($this->once())
            ->method('read')
            ->willReturn($expectedData);

        $accountRepository = new AccountRepository($jsonManagerMock);

        $actualData = $accountRepository->findAll();

        foreach ($actualData as $transaction) {
            $this->assertIsArray($transaction);
            $this->assertArrayHasKey('amount', $transaction);
            $this->assertIsInt($transaction['amount']);
            $this->assertArrayHasKey('date', $transaction);
            $this->assertIsString($transaction['date']);
        }
    }

    public function testGetBalanceReturnsZero(): void {
        $jsonManagerMock = $this->createMock(JsonManager::class);

        $jsonManagerMock
            ->expects($this->once())
            ->method('read')
            ->willReturn([]);

        $accountRepository = new AccountRepository($jsonManagerMock);

        $actualData = $accountRepository->getBalance();

        $this->assertEquals(0, $actualData);
    }

    public function testGetBalanceReturnsAddedBalance(): void {
        $expectedBalance = 20;
        $expectedData = [
            ['amount' => 13, 'date' => '2024-08-22 10:25:16'],
            ['amount' => 7, 'date' => '2024-08-22 10:25:16']
        ];

        $jsonManagerMock = $this->createMock(JsonManager::class);

        $jsonManagerMock
            ->expects($this->once())
            ->method('read')
            ->willReturn($expectedData);

        $accountRepository = new AccountRepository($jsonManagerMock);

        $actualBalance = $accountRepository->getBalance();

        $this->assertEquals($expectedBalance, $actualBalance);
    }
}