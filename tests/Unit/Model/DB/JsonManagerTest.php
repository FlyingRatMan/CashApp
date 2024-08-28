<?php
declare(strict_types=1);

namespace Unit\Model\DB;

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

        $this->assertEquals([], $actualData);
    }

    public function testReadReturnsData(): void {
        $testData = [
            ['key' => 'value']
        ];
        file_put_contents($this->testFilePath, json_encode($testData, JSON_THROW_ON_ERROR));

        $jsonManager = new JsonManager($this->testFilePath);

        $actualData = $jsonManager->read();

        $this->assertEquals($testData, $actualData);
    }

    public function testWriteNewFileAndWriteData(): void {
        $data = [
            'key' => 'value',
            'key2' => 'value2'
        ];

        $jsonManager = new JsonManager($this->testFilePath);

        $jsonManager->write($data);

        $actualData = json_decode(file_get_contents($this->testFilePath), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals([$data], $actualData);
    }

    public function testWriteToExistingFile(): void {
        $startingData = [
            'key' => 'value',
            'key2' => 'value2'
        ];
        file_put_contents($this->testFilePath, json_encode([$startingData], JSON_THROW_ON_ERROR));

        $newData = [
            'newkey' => 'newvalue',
            'newkey2' => 'newvalue2'
        ];

        $jsonManager = new JsonManager($this->testFilePath);

        $jsonManager->write($newData);

        $actualData = json_decode(file_get_contents($this->testFilePath), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals([$startingData, $newData], $actualData);
    }

    public function testWriteCatchAnError(): void {
        $jsonManager = new JsonManager($this->testFilePath);

        file_put_contents($this->testFilePath, '{invalid json}', JSON_THROW_ON_ERROR);

        $data = ['key' => 'value'];

        ob_start();

        $jsonManager->write($data);

        $output = ob_get_clean();
        $this->assertStringContainsString('JSON error:', $output);
    }
}