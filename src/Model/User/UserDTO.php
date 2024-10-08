<?php
declare(strict_types=1);

namespace App\Model\User;

readonly class UserDTO
{
    public function __construct(
        public int    $id,
        public string $name,
        public string $email,
        public string $password
    ) {}
}