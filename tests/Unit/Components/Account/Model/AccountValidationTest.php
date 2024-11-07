<?php
declare(strict_types=1);

namespace Unit\Components\Account\Model;

use App\Components\Account\Business\Model\AccountValidation;
use App\DataTransferObjects\AccountDTO;
use PHPUnit\Framework\TestCase;

class AccountValidationTest extends TestCase
{
    private AccountValidation $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = new AccountValidation();
    }

    public function testLimitReturnsNull(): void
    {
        $data = [];
        $amount = 100;

        $result = $this->validator->limit($data, $amount);

        $this->assertNull($result);
    }

    public function testDailyLimitExceeded(): void
    {
        $dailyLimit = 500;
        $data = [
            new AccountDTO(1, 1, $dailyLimit, date("Y-m-d H:i:s")),
        ];
        $amount = 10;

        $result = $this->validator->limit($data, $amount);

        $this->assertSame('Daily limit of 500 is exceeded.', $result->getMessage());
    }

    public function testHourlyLimitExceeded(): void
    {
        $hourlyLimit = 100;
        $data = [
            new AccountDTO(1, 1, $hourlyLimit, date("Y-m-d H:i:s")),
        ];
        $amount = 10;

        $result = $this->validator->limit($data, $amount);

        $this->assertSame('Hourly limit of 100 is exceeded.', $result->getMessage());
    }

    public function testValidAmountHasMoreThanTwoDecimals(): void
    {
        $amount = '10.000,000';

        $result = $this->validator->isValidAmount($amount);

        $this->assertEquals('Only two decimals are allowed', $result->getMessage());
    }

    public function testValidAmountReturnsNull(): void
    {
        $amount = '1.000.00';

        $result = $this->validator->isValidAmount($amount);

        $this->assertNull($result);
    }

    public function testValidAmountWithNoSeparatorsReturnsNull(): void
    {
        $amount = '10';

        $result = $this->validator->isValidAmount($amount);

        $this->assertNull($result );
    }
}