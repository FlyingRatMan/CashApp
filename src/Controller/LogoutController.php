<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;

readonly class LogoutController
{
    public function __construct(
        private View $view,
    ) {}
    public function index(): void
    {
        session_destroy();

        $this->view->setRedirect('/index.php?page=login');
    }
}