<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\Account\AccountEntityManager;
use App\Model\Account\AccountMapper;
use App\Model\Account\AccountRepository;
use App\Service\AccountValidatorInterface;

readonly class HomeController
{
    public function __construct(
        private View                      $view,
        private AccountEntityManager      $accountEntityManager,
        private AccountRepository         $accountRepository,
        private AccountValidatorInterface $accountValidator,
        private AccountMapper             $accountMapper,
    )
    {
    }

    public function index(): void
    {
        $id = $_SESSION['loggedUserId'];
        if ($_SESSION['loggedUser']) {
            $balance = $this->accountRepository->getBalance($id);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['loggedUser']) {
            $amount = $this->accountValidator->transform($_POST['amount']);
            $transfers = $this->accountRepository->findAll($id);

            $errors = [
                'limit' => $this->accountValidator->limit($transfers, (int)$_POST['amount']),
                'validAmount' => $this->accountValidator->isValidAmount($_POST['amount'])
            ];

            if ($errors['limit'] === '' && $errors['validAmount'] === '') {
                $transferDTO = $this->accountMapper->createAccountDTO([
                    'amount' => (float)$amount,
                    'date' => date('Y-m-d h:i:s')
                ]);

                $this->accountEntityManager->add($transferDTO, $id);

                $amount = '';
                $errors = [];
            }
        }

        $this->view->setTemplate('index.twig');

        $this->view->addParameter('loggedUser', $_SESSION['loggedUser'] ?? null);
        $this->view->addParameter('accBalance', $balance);
        $this->view->addParameter('amount', $amount ?? null);
        $this->view->addParameter('errors', $errors ?? null);
        $this->view->addParameter('submit', isset($_POST['submit']));
    }
}
