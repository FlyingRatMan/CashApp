<?php
declare(strict_types=1);

namespace Unit\Model\Account;

use App\Model\Account\AccountRepository;
use App\Model\DB\JsonManager;
use PHPUnit\Framework\TestCase;

class AccountRepositoryTest extends TestCase
{
    private string $testFilePath;

    protected function setUp(): void {
        parent::setUp();

        $this->testFilePath = __DIR__ . '/test.json';
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
    }

    protected function tearDown(): void {
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }

        parent::tearDown();
    }
    public function testFindAllReturnsEmpty(): void
    {
        $jsonManager = new JsonManager($this->testFilePath);
        $accountRepository = new AccountRepository($jsonManager);

        file_put_contents($this->testFilePath, json_encode([], JSON_THROW_ON_ERROR));
        $actualData = $accountRepository->findAll();

        $this->assertEmpty($actualData);
    }

    public function testFindAllReturnsTransactions(): void
    {
        $jsonManager = new JsonManager($this->testFilePath);
        $accountRepository = new AccountRepository($jsonManager);
        $expectedData = [
            ['amount' => 22, 'date' => '2024-08-22 10:25:16'],
            ['amount' => 22, 'date' => '2024-08-22 10:25:16']
        ];

        file_put_contents($this->testFilePath, json_encode($expectedData, JSON_THROW_ON_ERROR));
        $actualData = $accountRepository->findAll();

        foreach ($actualData as $transaction) {
            $arr = (array) $transaction;
            $this->assertIsArray($arr);
            $this->assertArrayHasKey('amount', $arr);
            $this->assertIsFloat($arr['amount']);
            $this->assertArrayHasKey('date', $arr);
            $this->assertIsString($arr['date']);
        }
    }

    public function testGetBalanceReturnsZero(): void {
        $jsonManager = new JsonManager($this->testFilePath);
        $accountRepository = new AccountRepository($jsonManager);

        file_put_contents($this->testFilePath, json_encode([], JSON_THROW_ON_ERROR));
        $actualData = $accountRepository->getBalance();

        $this->assertSame(0, $actualData);
    }

    public function testGetBalanceReturnsCorrectBalance(): void {
        $jsonManager = new JsonManager($this->testFilePath);
        $accountRepository = new AccountRepository($jsonManager);
        $expectedData = [
            ['amount' => 13, 'date' => '2024-08-22 10:25:16'],
            ['amount' => 7, 'date' => '2024-08-22 10:25:16']
        ];
        $expectedBalance = 20;

        file_put_contents($this->testFilePath, json_encode($expectedData, JSON_THROW_ON_ERROR));
        $actualBalance = $accountRepository->getBalance();

        $this->assertSame($expectedBalance, $actualBalance);
    }
}