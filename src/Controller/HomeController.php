<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Account\AccountEntityManager;
use App\Model\Account\AccountRepository;
use App\Service\AccountValidator;
use Twig\Environment;

readonly class HomeController
{
    public function __construct(
        private Environment          $twig,
        private AccountEntityManager $accountEntityManager,
        private AccountRepository    $accountRepository,
        private AccountValidator     $accountValidator,
    ) {}
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['loggedUser']) {
            $amount = $this->accountValidator->transform($_POST['amount']);
            $transfers = $this->accountRepository->findAll();

            $errors = [
                'limit' => $this->accountValidator->limit($transfers, (int)$_POST['amount']),
                'validAmount' => $this->accountValidator->isValidAmount($_POST['amount'])
            ];

            if ($errors['limit'] === '' && $errors['validAmount'] === '') {
                $transfer = [
                    'amount' => (float)$amount,
                    'date' => date('Y-m-d h:i:s')
                ];

                $this->accountEntityManager->add($transfer);

                $amount = '';
                $errors = [];
            }
        }

        $twigVars = [
            'loggedUser' => $_SESSION['loggedUser'] ?? null,
            'kontostand' => $this->accountRepository->getBalance(),
            'amount' => $amount ?? null,
            'errors' => $errors ?? null,
            'submit' => isset($_POST['submit'])
        ];

        echo $this->twig->render('index.twig', $twigVars);
    }
}
