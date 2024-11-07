<?php
declare(strict_types=1);

namespace App\Components\UserLogout\Communication;

use App\Core\View;

class UserLogoutController
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