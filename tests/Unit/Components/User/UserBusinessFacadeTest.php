<?php
declare(strict_types=1);

namespace Unit\Components\User;

use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Model\DB\SqlConnector;
use PHPUnit\Framework\TestCase;

class UserBusinessFacadeTest extends TestCase
{
    private UserBusinessFacade $userBusinessFacade;
    private UserRepository $userRepository;
    private UserMapper $userMapper;
    private SqlConnector $sqlConnector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sqlConnector = new SqlConnector();
        $this->userMapper = new UserMapper();
        $this->userRepository = new UserRepository($this->userMapper, $this->sqlConnector);
        $userEntityManager = new UserEntityManager($this->sqlConnector);
        $this->userBusinessFacade = new UserBusinessFacade($this->userRepository, $userEntityManager);

        $this->sqlConnector->insert("INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)",
            ['name' => 'user', 'email' => 'email@mail.com', 'password' => 'password',]);
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=0", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Users", []);
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=1", []);

        parent::tearDown();
    }

    public function testGetUserByEmailValid(): void
    {
        $expectedData = $this->userMapper->createUserDTO(['id' => 1, 'name' => 'user', 'email' => 'email@mail.com', 'password' => 'password']);

        $actualData = $this->userBusinessFacade->getUserByEmail('email@mail.com');

        $this->assertIsObject($actualData);
        $this->assertSame('user', $actualData->name);
        $this->assertSame('email@mail.com', $actualData->email);
    }

    public function testGetUserByEmailInvalid(): void
    {
        $actualData = $this->userRepository->getUserByEmail("test@test.com");

        $this->assertNull($actualData);
    }

    public function testSave(): void
    {
        $expectedData = $this->userMapper->createUserDTO(['id' => 1, 'name' => 'user', 'email' => 'newemail@mail.com', 'password' => 'password']);

        $this->userBusinessFacade->save($expectedData);
        $actualData = $this->userRepository->getUserByEmail('newemail@mail.com');

        $this->assertSame($expectedData->id, $actualData->id);
        $this->assertSame($expectedData->name, $actualData->name);
        $this->assertSame($expectedData->email, $actualData->email);
        $this->assertSame($expectedData->password, $actualData->password);
    }

    public function testUpdatePasswordValid(): void
    {
        $user = $this->userMapper->createUserDTO(['id' => 1, 'name' => 'user', 'email' => 'email@mail.com', 'password' => 'password']);
        $newPassword = 'new12QWqw,.';

        $actualData = $this->userBusinessFacade->updatePassword($user, $newPassword);

        $this->assertTrue($actualData);
    }
}