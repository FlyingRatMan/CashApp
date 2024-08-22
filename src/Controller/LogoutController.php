<?php
declare(strict_types=1);

namespace App\Controller;

readonly class LogoutController
{
    public function index(): void
    {
        session_destroy();

        header("Location: /");
        exit();
    }
}