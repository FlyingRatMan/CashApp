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
            'amount' => 50.0,
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
            $this->assertArrayHasKey('user_id', $arr);
            $this->assertSame(1, $arr['user_id']);
            $this->assertArrayHasKey('amount', $arr);
            $this->assertSame(50.0, $arr['amount']);
            $this->assertArrayHasKey('date', $arr);
            $this->assertSame('2024-08-01 00:00:00', $arr['date']);
        }
    }
}