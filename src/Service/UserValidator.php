<?php
declare(strict_types=1);

namespace App\Service;

use Error;

require __DIR__ . '/../../vendor/autoload.php';

class UserValidator
{
    public function isValidEmail(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Error('Invalid email address.');
        }

        return true;
    }

    public function isValidPassword(string $password): bool
    {
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/';

        if (!preg_match($pattern, $password)) {
            throw new Error("Password should be at least 6 characters long, and have special characters, numbers, 
                    capital and lower case letters.");
        }

        return true;
    }

    public function isValidCredentials(string $email,
                                       string $password,
                                       array $user): bool
    {
        if ($user['email'] === $email) {
            return password_verify($password, $user['password']);
        }

        return false;
    }

    /*public function validateUser(string $email, string $password): bool
    {
        if ($this->isValidEmail($email) && $this->isValidPassword($password)) {
            return true;
        }

        throw new Error('Invalid user credentials.');
    }*/
}