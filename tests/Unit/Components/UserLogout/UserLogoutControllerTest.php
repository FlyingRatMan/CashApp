<?php
declare(strict_types=1);

namespace Unit\Components\UserLogout;

use App\Components\UserLogout\Communication\UserLogoutController;
use App\Core\View;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserLogoutControllerTest extends TestCase
{
    private View $view;
    private UserLogoutController $logoutController;

    protected function setUp(): void
    {
        session_start();

        $loader = new FilesystemLoader('src/View/templates');
        $twig = new Environment($loader);

        $this->view = new View($twig);
        $this->logoutController = new UserLogoutController($this->view);
    }

    public function testIndex(): void
    {
        $this->logoutController->index();

        $redirect = $this->view->getRedirectTo();

        $this->assertSame('Location: /index.php?page=login', $redirect);
    }
}