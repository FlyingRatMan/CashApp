<?php
declare(strict_types=1);

namespace App\Components\Account\Business;

use App\Components\Account\Business\Model\AccountValidation;
use App\Components\Account\Persistence\AccountEntityManager;
use App\Components\Account\Persistence\AccountRepository;
use App\Components\Account\Persistence\Mapper\AccountMapper;
use App\Core\View;
use App\DataTransferObjects\AccountDTO;

class AccountBusinessFacade
{
    public function __construct(
        private View                 $view,
        private AccountEntityManager $entityManager,
        private AccountValidation    $accountValidation,
        private AccountRepository    $accountRepository,
        private AccountMapper        $accountMapper,
    ) {}

    public function add(AccountDTO $accountDTO, int $user_id): void
    {
        $amount = $accountDTO->amount;
        $transfers = $this->accountRepository->findAll($user_id);

        $limitError = $this->accountValidation->limit($transfers, (int)$amount);
        $amountError = $this->accountValidation->isValidAmount($_POST['amount']);
        $hasErrors = $limitError || $amountError;

        if (!$hasErrors) {
            $transferDTO = $this->accountMapper->createAccountDTO([
                'id' => 1,
                'user_id' => $user_id,
                'amount' => $amount,
                'date' => date('Y-m-d h:i:s')
            ]);

            $this->entityManager->add($transferDTO, $user_id);
        }

        if ($hasErrors) {
            $errors = [];
            if ($limitError) {$errors['limit'] = $limitError->getMessage();}
            if ($amountError) {$errors['validAmount'] = $amountError->getMessage();}

            $this->view->addParameter('amount', $amount);
            $this->view->addParameter('errors', $errors);
        }
    }
}