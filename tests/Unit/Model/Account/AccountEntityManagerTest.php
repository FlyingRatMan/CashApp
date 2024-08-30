<?php
declare(strict_types=1);

namespace Unit\Model\Account;

use App\Model\Account\AccountEntityManager;
use App\Model\DB\JsonManager;
use PHPUnit\Framework\TestCase;

class AccountEntityManagerTest extends TestCase
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
    public function testAdd(): void
    {
        $jsonManager = new JsonManager($this->testFilePath);
        $accountEntityManager = new AccountEntityManager($jsonManager);
        $expectedData = [
            'amount' => 100,
            'date' => '2024-08-28'
        ];

        $accountEntityManager->add($expectedData);
        $actualData = json_decode(file_get_contents($this->testFilePath), true,512, JSON_THROW_ON_ERROR);

        $this->assertCount(1, $actualData);
        $this->assertSame(100, $actualData[0]['amount']);
        $this->assertSame('2024-08-28', $actualData[0]['date']);
    }
}
