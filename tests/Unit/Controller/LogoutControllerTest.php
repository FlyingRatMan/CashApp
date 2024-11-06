<?php
/*declare(strict_types=1);

namespace Unit\Controller;

use App\Controller\LogoutController;
use App\Core\View;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class LogoutControllerTest extends TestCase
{
    private View $view;
    private LogoutController $logoutController;

    protected function setUp(): void
    {
        parent::setUp();
        session_start();

        $loader = new FilesystemLoader('src/View/templates');
        $twig = new Environment($loader);

        $this->view = new View($twig);
        $this->logoutController = new LogoutController($this->view);
    }

    public function testIndex(): void
    {
        $this->logoutController->index();

        $redirect = $this->view->getRedirectTo();

        $this->assertSame('Location: /index.php?page=login', $redirect);
    }
}*/