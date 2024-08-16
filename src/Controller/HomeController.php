<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\EntityManager\AccountEntityManager;
use App\Model\Repository\AccountRepository;
use App\Service\AccountValidator;

require __DIR__ . '/../../vendor/autoload.php';

class HomeController
{
    public function __construct(
        private $twig,
        private AccountEntityManager $accountEntityManager,
        private AccountRepository $accountRepository,
        private AccountValidator $accountValidator,
    ) {

    }
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = $this->accountValidator->transform($_POST['amount']);
            $transfers = $this->accountRepository->findAll();

            $errors = [
                'limit' => $this->accountValidator->limit($transfers, (int)$_POST['amount']),
                'validAmount' => $this->accountValidator->isValidAmount($_POST['amount'])
            ];

            $_SESSION['accErr'] = $errors;

            if ($errors['limit'] === '' && $errors['validAmount'] === '') {
                session_unset();

                $transfer = [
                    'amount' => (float)$amount,
                    'date' => date('Y-m-d h:i:s')
                ];

                $this->accountEntityManager->add($transfer);
            }
        }

        $twigVars = [
            'loggedUser' => $_SESSION['loggedUser'] ?? null,
            'kontostand' => $this->accountRepository->getBalance(),
            'amount' => $_SESSION['accErr'] ? $_POST['amount'] : '',
            'errors' => $_SESSION['accErr'] ?? null,
            'submit' => isset($_POST['submit'])
        ];

        echo $this->twig->render('index.twig', $twigVars);
    }
}
