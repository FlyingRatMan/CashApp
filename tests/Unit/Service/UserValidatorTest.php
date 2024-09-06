<?php
declare(strict_types=1);

namespace Unit\Service;

use App\Service\UserValidator;
use PHPUnit\Framework\TestCase;

class UserValidatorTest extends TestCase
{
    private UserValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new UserValidator();
    }

    public function testValidEmail(): void
    {
        $email = 'test@test.com';

        $result = $this->validator->isValidEmail($email);

        $this->assertSame('', $result);
    }

    public function testInvalidEmail(): void
    {
        $email = 'invalid-email';

        $result = $this->validator->isValidEmail($email);

        $this->assertSame('Invalid email address.', $result);
    }

    public function testValidPassword(): void
    {
        $password = '12QWqw,.';

        $result = $this->validator->isValidPassword($password);

        $this->assertSame('', $result);
    }

    public function testInvalidPassword(): void
    {
        $password = '123';

        $result = $this->validator->isValidPassword($password);

        $this->assertSame('Password should be at least 6 characters long, and have special characters, numbers, 
                    capital and lower case letters.', $result);
    }

    public function testValidCredentials(): void
    {
        $password = '12QWqw,.';
        $existingPassword = password_hash('12QWqw,.', PASSWORD_DEFAULT);

        $result = $this->validator->isValidCredentials($password, $existingPassword);

        $this->assertTrue($result);
    }

    public function testInvalidCredentials(): void
    {
        $password = '23WEwe,.';
        $existingPassword = password_hash('12QWqw,.', PASSWORD_DEFAULT);

        $result = $this->validator->isValidCredentials($password, $existingPassword);

        $this->assertFalse($result);
    }
}