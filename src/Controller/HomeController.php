<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\EntityManager\AccountEntityManager;
use App\Model\EntityManager\UserEntityManager;
use App\Model\Repository\AccountRepository;
use App\Model\Repository\UserRepository;
use App\Service\AccountValidator;
use App\Service\UserValidator;

require __DIR__ . '/../../vendor/autoload.php';

class HomeController
{
    public function __construct(
        private $twig,
        private AccountEntityManager $accountEntityManager,
        private AccountRepository $accountRepository,
        private AccountValidator $accountValidator,
//        private UserEntityManager $userEntityManager,
//        private UserValidator $userValidator,
//        private UserRepository $userRepository,
    ) {}
    public function index(): void
    {
        $twigVars = [
            'loggedUser' => $_SESSION['loggedUser'] ?? null,
            'kontostand' => $this->accountRepository->getBalance(),
            'amount' => $this->accountValidator->sanitize($_POST["amount"] ?? ''),
            'err' => '',
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

        $transfers && $errors = [
            'limit' => $this->accountValidator->limit($transfers, (int)$amount),
            'validAmount' => $this->accountValidator->isValidAmount($amount)
        ];

        if ($errors['limit'] === '' && $errors['validAmount'] === '') {
            $this->accountEntityManager->add(
                [
                    'amount' => (float)$amount,
                    'date' => date("Y-m-d h:i:s")
                ]);
        }
    }
}
