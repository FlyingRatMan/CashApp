<?php
declare(strict_types=1);

namespace Unit\Controller;

use App\Controller\HomeController;
use App\Model\Account\AccountEntityManager;
use App\Model\Account\AccountRepository;
use App\Service\AccountValidator;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class HomeControllerTest extends TestCase
{
    private $twig;
    private $accountEntityManager;
    private $accountRepository;
    private $accountValidator;
    private $controller;

    public function setUp(): void {
        $this->twig = $this->createMock(Environment::class);
        $this->accountEntityManager = $this->createMock(AccountEntityManager::class);
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->accountValidator = $this->createMock(AccountValidator::class);
        $this->controller = new HomeController($this->twig, $this->accountEntityManager, $this->accountRepository, $this->accountValidator);
    }

    public function testIndexWithoutPostRequest(): void {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['loggedUser'] = true;

        $this->accountRepository->expects($this->once())
            ->method('getBalance')
            ->willReturn(1000);

        $this->twig->expects($this->once())
            ->method('render')
            ->with('index.twig', [
                'loggedUser' => true,
                'kontostand' => 1000,
                'amount' => null,
                'errors' => null,
                'submit' => false
            ])
            ->willReturn('');

        $this->controller->index();
    }
}