<?php
declare(strict_types=1);

namespace Unit\Service;

use App\Model\Account\AccountDTO;
use App\Service\AccountValidator;
use PHPUnit\Framework\TestCase;

class AccountValidatorTest extends TestCase
{
    private AccountValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new AccountValidator();
    }

    public function testLimitReturnsNoErrors(): void
    {
        $data = [];
        $amount = 100;

        $result = $this->validator->limit($data, $amount);

        $this->assertSame('', $result);
    }

    public function testDailyLimitExceeded(): void
    {
        $data = [
            new AccountDTO(1, 1, 500, date("Y-m-d H:i:s")),
        ];
        $amount = 10;

        $result = $this->validator->limit($data, $amount);

        $this->assertSame('Daily limit of 500 is exceeded.', $result);
    }

    public function testHourlyLimitExceeded(): void
    {
        $data = [
            new AccountDTO(1, 1, 100, date("Y-m-d H:i:s")),
        ];
        $amount = 10;

        $result = $this->validator->limit($data, $amount);

        $this->assertSame('Hourly limit of 100 is exceeded.', $result);
    }

    public function testValidAmountHasMoreThanTwoDecimals(): void
    {
        $amount = '10.000,000';

        $result = $this->validator->isValidAmount($amount);

        $this->assertEquals('Only two decimals are allowed', $result);
    }

    public function testValidAmountReturnsNoErrors(): void
    {
        $amount = '1.000.00';

        $result = $this->validator->isValidAmount($amount);

        $this->assertSame('', $result );
    }

    public function testValidAmountWithNoSeparators(): void
    {
        $amount = '10';

        $result = $this->validator->isValidAmount($amount);

        $this->assertSame('', $result );
    }

    /*public function testSanitizeRemovesUnwantedChars(): void
    {
        $string = '    bc123\/xyz.45def   ';

        $result = $this->validator->sanitize($string);

        $this->assertSame('123.45', $result);
    }

    public function testSanitizeRemovesTags(): void
    {
        $string = '<script>alert()</script>123';

        $result = $this->validator->sanitize($string);

        $this->assertSame('123', $result);
    }

    public function testSanitizeWithProperNumber(): void
    {
        $string = '1.000,00';

        $result = $this->validator->sanitize($string);

        $this->assertSame('1.000,00', $result);
    }*/
}