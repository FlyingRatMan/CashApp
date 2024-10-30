<?php
declare(strict_types=1);

namespace Unit\Components\User;

use App\Components\User\Business\Model\UserValidation;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Model\DB\SqlConnector;
use PHPUnit\Framework\TestCase;

class UserValidationTest extends TestCase
{
    private UserValidation $validator;
    private SqlConnector $sqlConnector;

    protected function setUp(): void
    {
        parent::setUp();

        $userMapper = new UserMapper();
        $this->sqlConnector = new SqlConnector();
        $userRepository = new UserRepository($userMapper, $this->sqlConnector);
        $userEntityManager = new UserEntityManager($this->sqlConnector);
        $userBusinessFacade = new UserBusinessFacade($userRepository, $userEntityManager);
        $this->validator = new UserValidation($userBusinessFacade);

        $this->sqlConnector->insert("INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)",
            [':name' => 'test', ':email' => 'existing@test.com', ':password' => password_hash('12QWqw,.', PASSWORD_DEFAULT)]);
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=0", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Users", []);
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=1", []);

        parent::tearDown();
    }

    public function testVerifyPasswordValid(): void
    {
        $password = '12QWqw,.';
        $existingPassword = password_hash('12QWqw,.', PASSWORD_DEFAULT);

        $result = $this->validator->verifyPassword($password, $existingPassword);

        $this->assertTrue($result);
    }

    public function testVerifyPasswordInvalid(): void
    {
        $password = 'invalid';
        $existingPassword = password_hash('12QWqw,.', PASSWORD_DEFAULT);

        $result = $this->validator->verifyPassword($password, $existingPassword);

        $this->assertFalse($result);
    }

    public function testValidateEmailValid(): void
    {
        $email = 'test@test.com';

        $result = $this->validator->validateEmail($email);

        $this->assertNull($result);
    }

    public function testValidateEmailInvalid(): void
    {
        $email = 'invalid-email';

        $result = $this->validator->validateEmail($email);

        $this->assertSame('Invalid Email address.', $result->getMessage());
    }

    public function testValidatePasswordValid(): void
    {
        $password = '12QWqw,.';

        $result = $this->validator->validatePassword($password);

        $this->assertNull($result);
    }

    public function testValidatePasswordInvalid(): void
    {
        $password = '123';

        $result = $this->validator->validatePassword($password);

        $this->assertSame('Password should be at least 6 characters long, and have special characters, numbers, 
                    capital and lower case letters.', $result->getMessage());
    }

    public function testValidateCredentialsValid(): void
    {
        $email = 'newUser@test.com';
        $password = '12QWqw,.';

        $result = $this->validator->validateCredentials($email, $password);

        $this->assertNull($result);
    }

    public function testValidateCredentialsInvalid(): void
    {
        $email = 'invalid';
        $password = 'invalid';

        $result = $this->validator->validateCredentials($email, $password);

        $this->assertIsArray($result);
        $this->assertSame("Password should be at least 6 characters long, and have special characters, numbers, 
                    capital and lower case letters.", $result['password']);
        $this->assertSame('Invalid Email address.', $result['email']);
    }

    public function testValidateUserValid(): void
    {
        $email = 'existing@test.com';
        $password = '12QWqw,.';

        $result = $this->validator->validateUser($email, $password);

        $this->assertIsObject($result);
        $this->assertSame('existing@test.com', $result->email);
    }

    public function testValidateUserInvalid(): void
    {
        $email = 'existingUser@test.com';
        $password = 'invalid';

        $result = $this->validator->validateUser($email, $password);

        $this->assertNull($result);
    }
}