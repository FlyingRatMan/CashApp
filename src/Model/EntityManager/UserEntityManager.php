<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Repository\UserRepository;

require __DIR__ . '/../../../vendor/autoload.php';

class UserEntityManager
{
    // check in controller whether user already exist

    public function __construct(
        private JsonManager $jsonManager
    ) {}

    public function save(string $name, string $email, string $password)
    {
        $user = [
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
        ];

        $this->jsonManager->write($user);
    }

    /*public function update(string $name, array &$user): void
    {
        $this->jsonManager->update($name);
        $user['name'] = $name;
    }*/
}