<?php
/*declare(strict_types=1);

namespace Unit\Model\User;

use App\Model\DB\JsonManager;
use App\Model\User\UserEntityManager;
use PHPUnit\Framework\TestCase;

class UserEntityManagerTest extends TestCase
{
    public function testSave(): void {
        $expectedData = [
            'name' => 'name',
            'email' => 'email@mail.com',
            'password' => 'hashedPassword',
        ];

        $jsonManagerMock = $this->createMock(JsonManager::class);

        $jsonManagerMock
            ->expects($this->once())
            ->method('write')
            ->with($expectedData);

        $userEntityManager = new UserEntityManager($jsonManagerMock);

        $userEntityManager->save($expectedData);
    }
}*/