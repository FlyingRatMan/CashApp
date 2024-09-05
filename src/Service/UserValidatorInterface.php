<?php
declare(strict_types=1);

namespace App\Service;

interface UserValidatorInterface
{
    public function isValidEmail(string $email): string;

    public function isValidPassword(string $password): string;

    public function isValidCredentials(string $password, array $user): bool;
}