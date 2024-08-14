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
        private $errors = []
    ) {

    }
    public function index(): void
    {
        $twigVars = [
            'loggedUser' => $_SESSION['loggedUser'] ?? null,
            'kontostand' => $this->accountRepository->getBalance(),
            'amount' => '',
            'errors' => $_SESSION['accErr'] ?? null,
            'submit' => isset($_POST["submit"])
        ];

        echo $this->twig->render('index.twig', $twigVars);
    }

    /**
     * @throws \JsonException
     */
    public function transfer(): void
    {
        $amount = $this->accountValidator->transform($_POST['amount']);
        $transfers = $this->accountRepository->findAll();

        $this->errors = [
            'limit' => $this->accountValidator->limit($transfers, (int)$_POST['amount']),
            'validAmount' => $this->accountValidator->isValidAmount($_POST['amount'])
        ];

        $_SESSION['accErr'] = $this->errors;

        if ($this->errors['limit'] === '' && $this->errors['validAmount'] === '') {
            $transfer = [
                'amount' => (float)$amount,
                'date' => date("Y-m-d h:i:s")
            ];

            $this->accountEntityManager->add($transfer);

            header("Location: /");
            exit();
        }
    }
}
