<?php
declare(strict_types=1);

namespace App\Service;

use Error;

require __DIR__ . '/../../vendor/autoload.php';

class UserValidator
{
    public function isValidEmail(string $email): string
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email address.';
        }

        return '';
    }

    public function isValidPassword(string $password): string
    {
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/';

        if (!preg_match($pattern, $password)) {
            return"Password should be at least 6 characters long, and have special characters, numbers, 
                    capital and lower case letters.";
        }

        return '';
    }

    public function isValidCredentials(string $password, array $user): bool
    {
        return password_verify($password, $user['password']);
    }
}