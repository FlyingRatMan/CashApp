<?php
declare(strict_types=1);

namespace App\Components\Token\Business\Model;

use App\DBConnector\ORMEntityManager;
use App\Entity\TokenEntity;

class TokenValidation
{
    public function __construct(
    ) {}
    public function validateToken(string $email): bool {
        $repository = ORMEntityManager::getRepository(TokenEntity::class);

        $tokenEntity = $repository->findOneBy(['email' => $email]);

        if ($tokenEntity !== null) {
            $expiry = $tokenEntity->getExpiresAt();
            $now = date('Y-m-d H:i:s');

            if (new \DateTime($expiry) > new \DateTime($now)) {
                return true;
            }
        }

        return false;
    }
}