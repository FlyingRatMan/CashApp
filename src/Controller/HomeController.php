<?php
/*declare(strict_types=1);

namespace App\Controller;

use App\Components\Account\Business\Model\AccountValidation;
use App\Core\View;
use App\Model\Account\AccountEntityManager;
use App\Model\Account\AccountMapper;
use App\Model\Account\AccountRepository;


readonly class HomeController
{
    public function __construct(
        private View                 $view,
        private AccountEntityManager $accountEntityManager,
        private AccountRepository    $accountRepository,
        private AccountValidation    $accountValidator,
        private AccountMapper        $accountMapper,
    ) {}

    public function index(): void
    {
        if (!$_SESSION['loggedUser']) {
            $this->view->setRedirect('/index.php?page=login');
            return;
        }

        $user_id = $_SESSION['loggedUserId'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = $this->accountValidator->transform($_POST['amount']);
            $transfers = $this->accountRepository->findAll($user_id);

            $errors = [
                'limit' => $this->accountValidator->limit($transfers, (int)$_POST['amount']),
                'validAmount' => $this->accountValidator->isValidAmount($_POST['amount'])
            ];

            if ($errors['limit'] === '' && $errors['validAmount'] === '') {
                $transferDTO = $this->accountMapper->createAccountDTO([
                    'id' => 1,
                    'user_id' => $user_id,
                    'amount' => (float)$amount,
                    'date' => date('Y-m-d h:i:s')
                ]);

                $this->accountEntityManager->add($transferDTO, $user_id);

                $amount = '';
                $errors = [];
            }
        }

        $balance = $this->getBalance($user_id);

        $this->view->setTemplate('index.twig');

        $this->view->addParameter('loggedUser', $_SESSION['loggedUser']);
        $this->view->addParameter('accBalance', $balance);
        $this->view->addParameter('amount', $amount ?? null);
        $this->view->addParameter('errors', $errors ?? null);
        $this->view->addParameter('submit', isset($_POST['submit']));
    }

    public function getBalance(int $userID): int
    {
        $balance = 0;
        $transactions = $this->accountRepository->findAll($userID);

        foreach ($transactions as $transaction) {
            $balance += $transaction->amount;
        }

        return (int)$balance;
    }
}*/
