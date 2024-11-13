<?php
declare(strict_types=1);

namespace App\Components\Account\Persistence;

use App\DataTransferObjects\AccountDTO;
use App\DBConnector\ORMEntityManager;
use App\Entity\AccountEntity;

class AccountEntityManager
{
    public function __construct(
        private ORMEntityManager $ORMEntityManager,
    ) {}

    public function add(AccountDTO $accountDTO, $userID): void
    {
        $entityManager = $this->ORMEntityManager::getEntityManager();

        $accountEntity = new AccountEntity();
        $accountEntity->setUserID($userID);
        $accountEntity->setAmount($accountDTO->amount);
        $accountEntity->setDate($accountDTO->date);

        $entityManager->persist($accountEntity);
        $entityManager->flush();
    }
}