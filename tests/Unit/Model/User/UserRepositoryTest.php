<?php
/*declare(strict_types=1);

namespace Unit\Model\User;

use App\Model\DB\SqlConnector;
use App\Model\User\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private SqlConnector $sqlConnector;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sqlConnector = new SqlConnector();
        $this->userRepository = new UserRepository($this->sqlConnector);
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=0", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Users", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Account", []);
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=1", []);

        parent::tearDown();
    }

    public function testGetUserByEmailReturnsNull(): void
    {
        $actualData = $this->userRepository->getUserByEmail("test@test.com");

        $this->assertNull($actualData);
    }

    public function testGetUserByEmailReturnsUserDTO(): void
    {
        $expectedData = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
        ];
        $query = "INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)";
        $this->sqlConnector->insert($query, [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
        ]);

        $actualData = $this->userRepository->getUserByEmail('test@test.com');

        $this->assertSame($expectedData['name'], $actualData->name);
        $this->assertSame($expectedData['email'], $actualData->email);
        $this->assertSame($expectedData['password'], $actualData->password);
    }
}*/