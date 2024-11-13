<?php
declare(strict_types=1);

namespace Integration\Account;

use App\Components\Account\Business\AccountBusinessFacade;
use App\Components\Account\Business\Model\AccountService;
use App\Components\Account\Business\Model\AccountValidation;
use App\Components\Account\Communication\AccountController;
use App\Components\Account\Persistence\AccountEntityManager;
use App\Components\Account\Persistence\AccountRepository;
use App\Components\Account\Persistence\Mapper\AccountMapper;
use App\Core\View;
use App\DBConnector\ORMEntityManager;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class AccountControllerTest extends TestCase
{
    private View $view;
    private AccountController $controller;

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
        $accountRepository = new AccountRepository($accountMapper);
        $accountService = new AccountService($accountRepository);
        $accountBusinessFacade = new AccountBusinessFacade(
            $this->view,
            $accountEntityManager,
            $accountValidation,
            $accountRepository,
            $accountMapper
        );
        $this->controller = new AccountController($this->view, $accountService, $accountMapper, $accountBusinessFacade);
    }

    public function testIndexNoLoggedUser(): void
    {
        $_SESSION['loggedUser'] = null;

        $this->controller->index();
        $redirect = $this->view->getRedirectTo();

        $this->assertSame('Location: /index.php?page=login', $redirect);
    }

    public function testIndexWithLoggedUser(): void
    {
        $_SESSION['loggedUser'] = true;
        $_SESSION['loggedUserId'] = 1;
        $_SERVER['REQUEST_METHOD'] = "POST";
        $_POST['amount'] = '10';

        $this->controller->index();
        $template = $this->view->getTemplate();

        $this->assertSame('index.twig', $template);
    }
}