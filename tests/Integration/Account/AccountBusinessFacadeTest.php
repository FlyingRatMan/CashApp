<?php
declare(strict_types=1);

namespace Integration\Account;

use App\Components\Account\Business\AccountBusinessFacade;
use App\Components\Account\Business\Model\AccountValidation;
use App\Components\Account\Persistence\AccountEntityManager;
use App\Components\Account\Persistence\AccountRepository;
use App\Components\Account\Persistence\Mapper\AccountMapper;
use App\Core\View;
use App\DataTransferObjects\AccountDTO;
use App\DBConnector\ORMEntityManager;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class AccountBusinessFacadeTest extends TestCase
{
    private View $view;
    private AccountRepository $accountRepository;
    private AccountBusinessFacade $accountBusinessFacade;

    protected function setUp(): void
    {
        parent::setUp();

        $loader = new FilesystemLoader('/home/olhapurtova/PhpstormProjects/CashApp/src/View/templates');
        $twig = new Environment($loader);

        $this->view = new View($twig);
        $sqlConnector = new ORMEntityManager();
        $accountEntityManager = new AccountEntityManager($sqlConnector);
        $accountValidation = new AccountValidation();
        $accountMapper = new AccountMapper();
        $this->accountRepository = new AccountRepository($accountMapper);
        $this->accountBusinessFacade = new AccountBusinessFacade(
            $this->view,
            $accountEntityManager,
            $accountValidation,
            $this->accountRepository,
            $accountMapper
        );
    }

    public function testAddCallsValidationAndManager(): void
    {
        $accountDTO = new AccountDTO(1, 2, 10.0, '2024-11-07 00:00:00');
        $user_id = 1;
        $_POST['amount'] = '10';

        $this->accountBusinessFacade->add($accountDTO, $user_id);
        $actual = $this->accountRepository->findAll($user_id);

        $this->assertSame(10.0, $actual[3]->amount);
    }

    public function testAddWithInvalidAmountSetsParameter(): void
    {
        $accountDTO = new AccountDTO(1, 2, 1000.0, '2024-11-07 00:00:00');
        $user_id = 1;
        $_POST['amount'] = '1000';

        $this->accountBusinessFacade->add($accountDTO, $user_id);
        $param = $this->view->getParameters();

        $this->assertArrayHasKey('errors', $param);
    }
}