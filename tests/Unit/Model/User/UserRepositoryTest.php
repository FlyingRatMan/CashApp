<?php
declare(strict_types=1);

namespace Unit\Model\User;

use App\Model\DB\JsonManager;
use App\Model\User\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testGetUserByEmailReturnsEmpty(): void {
        $jsonManagerMock = $this->createMock(JsonManager::class);

        $jsonManagerMock
            ->expects($this->once())
            ->method('read')
            ->willReturn([]);

        $userRepository = new UserRepository($jsonManagerMock);

        $actualData = $userRepository->getUserByEmail('user@doesnt.exist');

        $this->assertEmpty($actualData);
    }

    public function testGetUserByEmailReturnsUser(): void {
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
        $jsonManagerMock = $this->createMock(JsonManager::class);

        $jsonManagerMock
            ->expects($this->once())
            ->method('read')
            ->willReturn($usersData);

        $userRepository = new UserRepository($jsonManagerMock);

        $actualData = $userRepository->getUserByEmail('email@mail.com');

        $this->assertEquals($expectedData, $actualData);
    }
}