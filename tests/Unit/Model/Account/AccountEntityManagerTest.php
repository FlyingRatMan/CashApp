<?php
declare(strict_types=1);

namespace Unit\Model\Account;

use App\Model\Account\AccountDTO;
use App\Model\Account\AccountEntityManager;
use App\Model\DB\SqlConnector;
use PHPUnit\Framework\TestCase;

class AccountEntityManagerTest extends TestCase
{
    private SqlConnector $sqlConnector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sqlConnector = new SqlConnector();
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
        $expectedData = new AccountDTO(100, '2024-01-01 00:00:00');
        $userID = 1;

        $insertUserQuery = "INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)";
        $this->sqlConnector->insert($insertUserQuery, [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $accountEntityManager->add($expectedData, $userID);
        $query = 'SELECT * FROM Account WHERE id = :userID';
        $actualData = $this->sqlConnector->select($query, ['userID' => $userID]);

        $this->assertCount(1, $actualData);
        $this->assertSame(100.0, $actualData[0]['amount']);
        $this->assertSame('2024-01-01 00:00:00', $actualData[0]['date']);
    }
}
