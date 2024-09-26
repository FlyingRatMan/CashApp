<?php
declare(strict_types=1);
session_start();

use App\Service\ControllerProvider;
use App\Core\View;
use DI\ContainerBuilder;

require_once __DIR__ . '/vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/config/config.php');
$container = $containerBuilder->build();

$controller = $_GET['page'] ?? 'home';

$controllerProvider = $container->get(ControllerProvider::class);
$controllerList = $controllerProvider->getList();
$controllerClass = $controllerList[$controller] ?? $controllerList['home'];

$controllerInit = $container->get($controllerClass);

$controllerInit->index();

$view = $container->get(View::class);
$output = $view->display();
if($output) {
    echo $output;
}