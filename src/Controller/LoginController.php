<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Repository\UserRepository;

require __DIR__ . '/../../vendor/autoload.php';

class LoginController
{
    public function __construct(
        private $twig,
    ) {}

    // index to show the login form
    // login to call a login method on a model and redirect to home page

    // in index file set up loader and twig vars, create new login controller and pass twig in it
    public function index(): void
    {
        echo 'login controller';
        //echo $this->twig->render('login.twig');
    }

    public function login(): void
    {

    }
}