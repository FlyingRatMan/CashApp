<?php
/*declare(strict_types=1);

namespace Unit\Model\User;

use App\Model\DB\SqlConnector;
use App\Model\User\UserDTO;
use App\Model\User\UserEntityManager;
use App\Model\User\UserRepository;
use PHPUnit\Framework\TestCase;

class UserEntityManagerTest extends TestCase
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

    public function testSave(): void
    {
        $userEntityManager = new UserEntityManager($this->sqlConnector);
        $expectedData = new UserDTO(1, 'name', 'email@mail.com', 'password');

        $userEntityManager->save($expectedData);
        $actualData = $this->userRepository->getUserByEmail('email@mail.com');

        $this->assertSame($expectedData->id, $actualData->id);
        $this->assertSame($expectedData->name, $actualData->name);
        $this->assertSame($expectedData->email, $actualData->email);
        $this->assertSame($expectedData->password, $actualData->password);
    }
}*/