<?php
declare(strict_types=1);

use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\RegistrationController;
use App\Core\View;
use App\Model\Account\AccountEntityManager;
use App\Model\Account\AccountMapper;
use App\Model\Account\AccountRepository;
use App\Model\DB\JsonManager;
use App\Model\User\UserEntityManager;
use App\Model\User\UserMapper;
use App\Model\User\UserRepository;
use App\Service\AccountValidator;
use App\Service\UserValidator;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    FilesystemLoader::class => \DI\create(FilesystemLoader::class)
        ->constructor('src/View/templates'),

    Environment::class => \DI\create(Environment::class)
        ->constructor(\DI\get(FilesystemLoader::class)),

    'UserJson' => \DI\create(JsonManager::class)
        ->constructor('users.json'),

    'AccountJson' => \DI\create(JsonManager::class)
        ->constructor('account.json'),

    AccountEntityManager::class => \DI\autowire()
        ->constructorParameter('jsonManager', \DI\get('AccountJson')),
    UserEntityManager::class => \DI\autowire()
        ->constructorParameter('jsonManager', \DI\get('UserJson')),

    AccountRepository::class => \DI\autowire()
        ->constructorParameter('jsonManager', \DI\get('AccountJson')),
    UserRepository::class => \DI\autowire()
        ->constructorParameter('jsonManager', \DI\get('UserJson')),

    View::class => \DI\autowire(),

    AccountValidator::class => \DI\autowire(),
    UserValidator::class => \DI\autowire(),

    UserMapper::class => \DI\autowire(),
    AccountMapper::class => \DI\autowire(),

    HomeController::class => \DI\autowire(),
    RegistrationController::class => \DI\autowire(),
    LoginController::class => \DI\autowire(),
    LogoutController::class => \DI\autowire(),
];