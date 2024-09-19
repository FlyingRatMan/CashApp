<?php
declare(strict_types=1);

namespace Unit\Model\DB;

use App\Model\DB\SqlConnector;
use PDOException;
use PHPUnit\Framework\TestCase;

class SqlConnectorTest extends TestCase
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

    public function testInsertSuccessful(): void
    {
        $query = "INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)";
        $params = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'hashedPassword'
        ];

        $insert = $this->sqlConnector->insert($query, $params);

        $this->assertTrue($insert);
    }

    public function testInsertFailed(): void
    {
        $query = "INSERT INTO Account (user_id, amount, date) VALUES (:user_id, :amount, :date)";
        $params = [
            'user_id' => 999,
            'amount' => 100.0,
            'date' => '2024-01-01 00:00:00',
        ];

        $this->expectException(PDOException::class);
        $this->sqlConnector->insert($query, $params);
    }
}