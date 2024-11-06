<?php
declare(strict_types=1);

namespace Unit\Components\User;

use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\db_script;
use App\Model\DB\SqlConnector;
use PHPUnit\Framework\TestCase;

class UserBusinessFacadeTest extends TestCase
{
    private UserBusinessFacade $userBusinessFacade;
    private UserRepository $userRepository;
    private UserMapper $userMapper;
    private db_script $db_script;

    protected function setUp(): void
    {
        parent::setUp();

        $sqlConnector = new SqlConnector();
        $this->userMapper = new UserMapper();
        $this->userRepository = new UserRepository($this->userMapper, $sqlConnector);
        $userEntityManager = new UserEntityManager($sqlConnector);
        $this->userBusinessFacade = new UserBusinessFacade($this->userRepository, $userEntityManager);

        $this->db_script = new db_script();
        $this->db_script->prefillDatabase();
    }

    protected function tearDown(): void
    {
        $this->db_script->cleanDatabase();

        parent::tearDown();
    }

    public function testGetUserByEmailValid(): void
    {
        $actualData = $this->userBusinessFacade->getUserByEmail('max@example.com');

        $this->assertIsObject($actualData);
        $this->assertSame('Max Mustermann', $actualData->name);
        $this->assertSame('max@example.com', $actualData->email);
    }

    public function testGetUserByEmailInvalid(): void
    {
        $actualData = $this->userRepository->getUserByEmail("user@doesnt.exist");

        $this->assertNull($actualData);
    }

    public function testSave(): void
    {
        $expectedData = $this->userMapper->createUserDTO(['id' => 1, 'name' => 'Test', 'email' => 'testSave@saves.com', 'password' => 'password']);

        $this->userBusinessFacade->save($expectedData);
        $actualData = $this->userRepository->getUserByEmail('testSave@saves.com');

        $this->assertSame($expectedData->name, $actualData->name);
        $this->assertSame($expectedData->email, $actualData->email);
        $this->assertSame($expectedData->password, $actualData->password);
    }

    public function testUpdatePasswordValid(): void
    {
        $user = $this->userMapper->createUserDTO(['id' => 1, 'name' => 'Max Mustermann', 'email' => 'max@example.com', 'password' => password_hash('12QWqw,.', PASSWORD_DEFAULT)]);
        $newPassword = password_hash('new12QWqw,.', PASSWORD_DEFAULT);

        $actualData = $this->userBusinessFacade->updatePassword($user, $newPassword);

        $this->assertTrue($actualData);
    }
}