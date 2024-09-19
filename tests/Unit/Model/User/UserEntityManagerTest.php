<?php
declare(strict_types=1);

namespace Unit\Model\User;

use App\Model\DB\SqlConnector;
use App\Model\User\UserDTO;
use App\Model\User\UserEntityManager;
use PHPUnit\Framework\TestCase;

class UserEntityManagerTest extends TestCase
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

    public function testSave(): void
    {
        $userEntityManager = new UserEntityManager($this->sqlConnector);
        $expectedData = new UserDTO(1, 'name', 'email', 'password');

        $userEntityManager->save($expectedData);
        $actualData = $this->sqlConnector->select("SELECT * FROM Users WHERE id = :id", ['id' => 1]);

        $this->assertCount(1, $actualData);
        $this->assertSame((array)$expectedData, $actualData[0]);
    }
}