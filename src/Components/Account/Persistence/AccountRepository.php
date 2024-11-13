<?php
declare(strict_types=1);

namespace App\Components\Account\Persistence;

use App\Components\Account\Persistence\Mapper\AccountMapper;
use App\DBConnector\ORMEntityManager;
use App\Entity\AccountEntity;

class AccountRepository
{
    public function __construct(
        private AccountMapper $accountMapper
    ) {}

    public function findAll(int $userID): array
    {
        $repository = ORMEntityManager::getRepository(AccountEntity::class);

        $transactions = $repository->findBy(['userId' => $userID]);

        if (empty($transactions)) {
            return [];
        }

        $listOfTransactions = [];
        foreach ($transactions as $transaction) {
            $accountDTO = $this->accountMapper->createAccountDTO(
                [
                    'id' => $transaction->getId(),
                    'user_id' => $transaction->getUserId(),
                    'amount' => $transaction->getAmount(),
                    'date' => $transaction->getDate(),
                ]);
            $listOfTransactions[] = $accountDTO;
        }

        return $listOfTransactions;
    }
}