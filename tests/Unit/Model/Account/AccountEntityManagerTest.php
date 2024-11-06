<?php
/*declare(strict_types=1);

namespace Unit\Model\Account;

use App\Model\Account\AccountDTO;
use App\Model\Account\AccountEntityManager;
use App\Model\Account\AccountRepository;
use App\Model\DB\SqlConnector;
use PHPUnit\Framework\TestCase;

class AccountEntityManagerTest extends TestCase
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

    public function testAdd(): void
    {
        $accountEntityManager = new AccountEntityManager($this->sqlConnector);
        $expectedData = new AccountDTO(1,1, 100, '2024-01-01 00:00:00');
        $userID = 1;

        $insertUserQuery = "INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)";
        $this->sqlConnector->insert($insertUserQuery, [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $accountEntityManager->add($expectedData, $userID);
        $actualData = $this->accountRepository->findAll($userID);

        $this->assertCount(1, $actualData);
        $this->assertSame($expectedData->user_id, $userID);
        $this->assertSame($expectedData->amount, $actualData[0]->amount);
        $this->assertSame($expectedData->date, $actualData[0]->date);
    }
}*/
