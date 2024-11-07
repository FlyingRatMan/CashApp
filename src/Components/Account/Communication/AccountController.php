<?php
declare(strict_types=1);

namespace App\Components\Account\Communication;

use App\Components\Account\Business\AccountBusinessFacade;
use App\Components\Account\Business\Model\AccountService;
use App\Components\Account\Persistence\AccountEntityManager;
use App\Components\Account\Persistence\Mapper\AccountMapper;
use App\Core\View;

class AccountController
{
    public function __construct(
        private View                  $view,
        private AccountService        $accountService,
        private AccountMapper         $accountMapper,
        private AccountBusinessFacade $accountFacade,
    ) {}

    public function index(): void
    {
        if (!$_SESSION['loggedUser']) {
            $this->view->setRedirect('/index.php?page=login');
            return;
        }

        $user_id = $_SESSION['loggedUserId'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newTransfer = [
                'id' => 1,
                'user_id' => $user_id,
                'amount' => (float)$_POST['amount'],
                'date' => date('Y-m-d h:i:s')
            ];

            $accountDTO = $this->accountMapper->createAccountDTO($newTransfer);

            $this->accountFacade->add($accountDTO, $user_id);
        }

        $balance = $this->accountService->getBalance($user_id);

        $this->view->setTemplate('index.twig');

        $this->view->addParameter('loggedUser', $_SESSION['loggedUser']);
        $this->view->addParameter('accBalance', $balance);
        $this->view->addParameter('submit', isset($_POST['submit']));
    }
}