<?php
declare(strict_types=1);

namespace Unit\Model\User;

use App\Model\DB\JsonManager;
use App\Model\User\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private string $testFilePath;

    protected function setUp(): void {
        parent::setUp();

        $this->testFilePath = __DIR__ . '/test.json';
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
    }

    protected function tearDown(): void {
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }

        parent::tearDown();
    }
    public function testGetUserByEmailReturnsNull(): void {
        $jsonManager = new JsonManager($this->testFilePath);
        $userRepository = new UserRepository($jsonManager);

        file_put_contents($this->testFilePath, json_encode([], JSON_THROW_ON_ERROR));
        $actualData = $userRepository->getUserByEmail('user@doesnt.exist');

        $this->assertNull($actualData);
    }

    public function testGetUserByEmailReturnsUserDTO(): void {
        $jsonManager = new JsonManager($this->testFilePath);
        $userRepository = new UserRepository($jsonManager);
        $expectedData = [
            'name' => 'name',
            'email' => 'email@mail.com',
            'password' => 'hashedPassword',
        ];
        $usersData = [
            $expectedData,
            [
                'name' => 'name',
                'email' => 'another@mail.com',
                'password' => 'hashedPassword',
            ]
        ];

        file_put_contents($this->testFilePath, json_encode($usersData, JSON_THROW_ON_ERROR));
        $actualData = $userRepository->getUserByEmail('email@mail.com');

        $this->assertSame($expectedData, (array)$actualData);
    }
}