<?php
declare(strict_types=1);
session_start();

use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\RegistrationController;

require_once __DIR__ . '/vendor/autoload.php';

use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/config/config.php');

$container = $containerBuilder->build();

$homeController = $container->get(HomeController::class);
$loginController = $container->get(LoginController::class);
$logoutController = $container->get(LogoutController::class);
$registrationController = $container->get(RegistrationController::class);

$controller = $_GET['page'] ?? 'home';

$controllerInit = match ($controller) {
    'login' => $loginController,
    'register' => $registrationController,
    'logout' => $logoutController,
    'home' => $homeController,
};

$controllerInit->index();
