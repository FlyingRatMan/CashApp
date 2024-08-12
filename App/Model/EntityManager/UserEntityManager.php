<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Repository\UserRepository;

require __DIR__ . '/../../../vendor/autoload.php';

class UserEntityManager
{
    public function __construct(
        private UserRepository $userRepository,
        private JsonManager $jsonManager
    ) {}

    public function save(string $name, string $email, string $password)
    {
        $user = [
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
        ];

        if ($existingUser = $this->userRepository->getUserByEmail($email)) {
            throw new \Error('User already exist.');
        }

        $this->jsonManager->write($user);
    }
}