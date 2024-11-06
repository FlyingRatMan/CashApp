<?php
declare(strict_types=1);

namespace Unit\Components\Token;

use App\Components\Token\Business\Model\TokenValidation;
use App\Model\DB\SqlConnector;
use PHPUnit\Framework\TestCase;

class TokenValidationTest extends TestCase
{
    private TokenValidation $validation;

    protected function setUp(): void
    {
        $sqlConnector = new SqlConnector();
        $this->validation = new TokenValidation($sqlConnector);
    }

    public function testValidToken(): void {
        $email = 'update@example.com';

        $actual = $this->validation->ValidateToken($email);

        $this->assertTrue($actual);
    }

    public function testInvalidToken(): void {
        $email = 'max@example.com';

        $actual = $this->validation->ValidateToken($email);

        $this->assertFalse($actual);
    }

    public function testInvalidEmail(): void {
        $email = 'invalid@example.com';

        $actual = $this->validation->ValidateToken($email);

        $this->assertFalse($actual);
    }
}