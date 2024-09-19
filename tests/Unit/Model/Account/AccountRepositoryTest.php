<?php
declare(strict_types=1);

namespace Unit\Model\Account;

use App\Model\Account\AccountRepository;
use App\Model\DB\SqlConnector;
use PHPUnit\Framework\TestCase;

class AccountRepositoryTest extends TestCase
{
    private SqlConnector $sqlConnector;
    private AccountRepository $accountRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sqlConnector = new SqlConnector();
        $this->accountRepository = new AccountRepository($this->sqlConnector);
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=0", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Users", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Account", []);
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=1", []);

        parent::tearDown();
    }

    public function testFindAllReturnsEmpty(): void
    {
        $userID = 0;

        $actualData = $this->accountRepository->findAll($userID);

        $this->assertEmpty($actualData);
    }

    public function testFindAllReturnsTransactions(): void
    {
        $query = "INSERT INTO Account (user_id, amount, date) 
            VALUES (:user_id, :amount, :date)";
        $expectedData = [
            'user_id' => 1,
            'amount' => 50,
            'date' => '2024-08-01 00:00:00'
        ];

        $insertUserQuery = "INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)";
        $this->sqlConnector->insert($insertUserQuery, [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->sqlConnector->insert($query, $expectedData);
        $actualData = $this->accountRepository->findAll(1);

        foreach ($actualData as $transaction) {
            $arr = (array)$transaction;
            $this->assertIsArray($arr);
            $this->assertArrayHasKey('amount', $arr);
            $this->assertIsFloat($arr['amount']);
            $this->assertArrayHasKey('date', $arr);
            $this->assertIsString($arr['date']);
        }
    }

    /*public function testGetBalanceReturnsZero(): void
    {


        $jsonManager = new JsonManager($this->testFilePath);
        $accountRepository = new AccountRepository($jsonManager);

        file_put_contents($this->testFilePath, json_encode([], JSON_THROW_ON_ERROR));
        $actualData = $accountRepository->getBalance();

        $this->assertSame(0, $actualData);
    }

    public function testGetBalanceReturnsCorrectBalance(): void
    {


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
    }*/
}