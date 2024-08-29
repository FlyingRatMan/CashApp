<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
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

        $view = new View($this->twig);
        $view->addParameter('loggedUser', $_SESSION['loggedUser'] ?? null);
        $view->addParameter('accBalance', $this->accountRepository->getBalance());
        $view->addParameter('amount', $amount ?? null);
        $view->addParameter('errors', $errors ?? null);
        $view->addParameter('submit', isset($_POST['submit']));

        $view->display('index');
    }
}
