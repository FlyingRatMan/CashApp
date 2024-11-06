<?php
/*declare(strict_types=1);

namespace Unit\Controller;

use App\Controller\HomeController;
use App\Core\View;
use App\Model\Account\AccountDTO;
use App\Model\Account\AccountEntityManager;
use App\Model\Account\AccountMapper;
use App\Model\Account\AccountRepository;
use App\Model\DB\SqlConnector;
use App\Model\User\UserDTO;
use App\Service\AccountValidator;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HomeControllerTest extends TestCase
{
    private SqlConnector $sqlConnector;
    private View $view;
    private HomeController $homeController;
    private AccountEntityManager $accountEntityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $loader = new FilesystemLoader('src/View/templates');
        $twig = new Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);

        $this->sqlConnector = new SqlConnector();
        $this->view = new View($twig);
        $this->accountEntityManager = new AccountEntityManager($this->sqlConnector);
        $accountRepository = new AccountRepository($this->sqlConnector);
        $accountValidator = new AccountValidator();
        $accountMapper = new AccountMapper();
        $this->homeController = new HomeController(
            $this->view,
            $this->accountEntityManager,
            $accountRepository,
            $accountValidator,
            $accountMapper
        );

        $insertUserQuery = "INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)";
        $this->sqlConnector->insert($insertUserQuery, [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=0", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Users", []);
        $this->sqlConnector->insert("TRUNCATE TABLE Account", []);
        $this->sqlConnector->insert("SET FOREIGN_KEY_CHECKS=1", []);

        parent::tearDown();
    }

    public function testGetBalanceReturnsCorrectBalance(): void
    {
        $accountDTO = new AccountDTO(1, 1, 50.0, '2024-01-01 00:00:00');

        $this->accountEntityManager->add($accountDTO, 1);
        $actualData = $this->homeController->getBalance(1);

        $this->assertSame(50, $actualData);
    }

    public function testGetBalanceReturnsZero(): void
    {
        $accountDTO = new AccountDTO(1, 1, 50.0, '2024-01-01 00:00:00');

        $this->accountEntityManager->add($accountDTO, 1);
        $actualData = $this->homeController->getBalance(777);

        $this->assertSame(0, $actualData);
    }

    public function testRedirectIfNoUser(): void
    {
        $_SESSION['loggedUser'] = false;
        $this->homeController->index();

        $redirect = $this->view->getRedirectTo();

        $this->assertSame('Location: /index.php?page=login', $redirect);
    }

    public function testIndexPostWithNoErrors(): void
    {
        $_SESSION['loggedUser'] = new UserDTO(1, 'test', 'test@t.de', 'password');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['amount'] = '50';
        $_SESSION['loggedUserId'] = 1;

        $this->homeController->index();

        $template = $this->view->getTemplate();
        $parameters = $this->view->getParameters();

        $this->assertSame('index.twig', $template);
        $this->assertEmpty($parameters['errors']);
        $this->assertSame(50, $parameters['accBalance']);
    }

    public function testIndexPostWithErrors(): void
    {
        $_SESSION['loggedUser'] = new UserDTO(1, 'test', 'test@t.de', 'password');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['amount'] = '999999';
        $_SESSION['loggedUserId'] = 1;

        $this->homeController->index();

        $template = $this->view->getTemplate();
        $parameters = $this->view->getParameters();

        $this->assertSame('index.twig', $template);
        $this->assertNotEmpty($parameters['errors']['limit']);
        $this->assertSame(0, $parameters['accBalance']);
    }
}*/