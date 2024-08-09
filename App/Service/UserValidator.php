<?php

namespace src\Service;

use Error;

class UserValidator
{
    public function isValidEmail($email): string
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Error('Invalid email address.');
        }

        return true;
    }

    public function isValidPassword($password): string
    {
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/';

        if (!preg_match($pattern, $password)) {
            throw new Error("Password should be at least 6 characters long, and have special characters, numbers, 
                    capital and lower case letters.");
        }

        return true;
    }

    public function validateUser($email, $password)
    {
        if ($this->isValidEmail($email) && $this->isValidPassword($password)) {
            return true;
        }
    }
}