<?php
declare(strict_types=1);

namespace Unit\Model\User;

use App\Model\DB\JsonManager;
use App\Model\User\UserEntityManager;
use PHPUnit\Framework\TestCase;

class UserEntityManagerTest extends TestCase
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
    public function testSave(): void {
        $jsonManager = new JsonManager($this->testFilePath);
        $userEntityManager = new UserEntityManager($jsonManager);
        $expectedData = [
            'name' => 'name',
            'email' => 'email@mail.com',
            'password' => 'hashedPassword',
        ];

        $userEntityManager->save($expectedData);
        $actualData = json_decode(file_get_contents($this->testFilePath), true, 512, JSON_THROW_ON_ERROR);

        $this->assertCount(1, $actualData);
        $this->assertSame($expectedData, $actualData[0]);
    }
}