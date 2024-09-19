<?php
declare(strict_types=1);

namespace Unit\Model\Mapper;

use App\Model\User\UserMapper;
use PHPUnit\Framework\TestCase;

class UserMapperTest extends TestCase
{
    public function testCreateDTOReturnsUserDTO(): void
    {
        $mapper = new UserMapper();
        $expectedUser = [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'hashed_password',
        ];

        $actualUserDTO = $mapper->createUserDTO($expectedUser);

        $this->assertSame($expectedUser['name'], $actualUserDTO->name);
        $this->assertSame($expectedUser['email'], $actualUserDTO->email);
        $this->assertSame($expectedUser['password'], $actualUserDTO->password);
    }
}