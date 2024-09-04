<?php
declare(strict_types=1);

namespace Unit\Model\DB;

use App\Model\Account\AccountDTO;
use App\Model\DB\JsonManager;
use PHPUnit\Framework\TestCase;

class JsonManagerTest extends TestCase
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

    public function testReadReturnsEmpty(): void {
        $nonExistentFilePath = __DIR__ . '/nonexistentfile.json';
        $jsonManager = new JsonManager($nonExistentFilePath);

        $actualData = $jsonManager->read();

        $this->assertSame([], $actualData);
    }

    public function testReadReturnsData(): void {
        $testData = [
            ['key' => 'value']
        ];
        $jsonManager = new JsonManager($this->testFilePath);

        file_put_contents($this->testFilePath, json_encode($testData, JSON_THROW_ON_ERROR));
        $actualData = $jsonManager->read();

        $this->assertCount(1, $actualData);
        $this->assertSame('value', $actualData[0]['key']);
    }

    public function testWriteNewFileAndWriteData(): void {
        $data = [
            'key' => 'value',
            'key2' => 'value2'];
        $jsonManager = new JsonManager($this->testFilePath);

        $jsonManager->write($data);
        $actualData = json_decode(file_get_contents($this->testFilePath), true, 512, JSON_THROW_ON_ERROR);

        $this->assertCount(1, $actualData);
        $this->assertSame('value', $actualData[0]['key']);
        $this->assertSame('value2', $actualData[0]['key2']);
    }

    public function testWriteToExistingFile(): void {
        $startingData = [
            'key' => 'value',
            'key2' => 'value2'
        ];
        $newData = [
            'newkey' => 'newvalue',
            'newkey2' => 'newvalue2'];
        $jsonManager = new JsonManager($this->testFilePath);

        file_put_contents($this->testFilePath, json_encode([$startingData], JSON_THROW_ON_ERROR));
        $jsonManager->write($newData);
        $actualData = json_decode(file_get_contents($this->testFilePath), true, 512, JSON_THROW_ON_ERROR);

        $this->assertCount(2, $actualData);
        $balance1 = $actualData[0];
        $this->assertSame('value', $balance1['key']);
        $this->assertSame('value2', $balance1['key2']);
        $balance2 = $actualData[1];
        $this->assertSame('newvalue', $balance2['newkey']);
        $this->assertSame('newvalue2', $balance2['newkey2']);
    }

    public function testWriteCatchAnError(): void {
        $jsonManager = new JsonManager($this->testFilePath);
        $data = ['key' => 'value'];

        file_put_contents($this->testFilePath, '{invalid json}', JSON_THROW_ON_ERROR);
        ob_start();
        $jsonManager->write($data);

        $output = ob_get_clean();
        $this->assertStringContainsString('JSON error:', $output);
    }
}