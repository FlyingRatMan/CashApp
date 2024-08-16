<?php
declare(strict_types=1);

namespace App\Controller;
require __DIR__ . '/../../vendor/autoload.php';

class LogoutController
{
    public function __construct(

    ) {}

    public function index(): void
    {
        session_destroy();

        header("Location: /");
        exit();
    }
}