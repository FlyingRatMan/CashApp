<?php
declare(strict_types=1);
session_start();

use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\RegistrationController;

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\View;
use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/config/config.php');

$container = $containerBuilder->build();

$controller = $_GET['page'] ?? 'home';

$controllerInit = match ($controller) {
    'login' => $container->get(LoginController::class),
    'register' => $container->get(RegistrationController::class),
    'logout' => $container->get(LogoutController::class),
    'home' => $container->get(HomeController::class),
};

$view = $container->get(View::class);

$controllerInit->index();

$view->display();

// TODO repository should return dto?