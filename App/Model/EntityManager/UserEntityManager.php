<?php
declare(strict_types=1);

namespace src\Model\EntityManager;

use Error;
use src\Model\Repository\UserRepository;

class UserEntityManager
{
    public function __construct(
        private UserRepository $userRepository,
        private JsonManager $jsonManager
    ) {}

    public function save(string $name, string $email, string $password)
    {
        if ($this->userRepository->getUserByEmail($email)) {
            throw new Error("User already exists");
        }

        $user = [
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
        ];

        $this->jsonManager->writeJson($user);
    }
}