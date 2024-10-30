<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model;

use App\Components\User\Business\UserBusinessFacade;
use App\DataTransferObjects\UserDTO;
use Error;

class UserValidation
{
    public function __construct(
        private UserBusinessFacade $userFacade
    ){}

    public function verifyPassword(string $password, string $savedPassword): bool
    {
        return password_verify($password, $savedPassword);
    }

    public function validateEmail(string $email): ?Error
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Error('Invalid Email address.');
        }

        return null;
    }

    public function validatePassword(string $password): ?Error
    {
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/';

        if (!preg_match($pattern, $password)) {
            return new Error("Password should be at least 6 characters long, and have special characters, numbers, 
                    capital and lower case letters.");
        }

        return null;
    }

    public function validateCredentials(string $email, string $password): ?array
    {
        $userExist = $this->userFacade->getUserByEmail($email);
        $emailError = $this->validateEmail($email);
        $passwordError = $this->validatePassword($password);
        $hasErrors = $emailError !== null || $passwordError !== null;

        if ($userExist || $hasErrors) {
            $errors = [];
            if ($emailError) {$errors['email'] = $emailError->getMessage();}
            if ($passwordError) {$errors['password'] = $passwordError->getMessage();}
            return $errors;
        }

        return null;
    }

    public function validateUser(string $email, string $password): ?UserDTO
    {
        $user = $this->userFacade->getUserByEmail($email);

        if ($user !== null) {
            $validUser = $this->verifyPassword($password, $user->password);
        }

        return $validUser ? $user : null;
    }
}