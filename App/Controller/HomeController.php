<?php
declare(strict_types=1);

namespace App\Controller;
require __DIR__ . '/../../vendor/autoload.php';



class HomeController
{
    public function index(): void
    {
        echo 'home controller';
    }
}