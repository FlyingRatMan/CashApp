<?php
declare(strict_types=1);

use App\Controller\ForgotPasswordController;
use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\ResetPasswordController;
use App\Controller\RegistrationController;
use App\Core\View;
use App\Model\Account\AccountEntityManager;
use App\Model\Account\AccountMapper;
use App\Model\Account\AccountRepository;
use App\Model\DB\SqlConnector;
use App\Model\User\UserEntityManager;
use App\Model\User\UserMapper;
use App\Model\User\UserRepository;
use App\Service\AccountValidator;
use App\Service\AccountValidatorInterface;
use App\Service\ControllerProvider;
use App\Service\UserValidator;
use App\Service\UserValidatorInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    FilesystemLoader::class => \DI\create(FilesystemLoader::class)
        ->constructor('src/View/templates'),

    Environment::class => \DI\create(Environment::class)
        ->constructor(\DI\get(FilesystemLoader::class)),

    SqlConnector::class => \DI\create(SqlConnector::class),

    AccountEntityManager::class => \DI\autowire(),
    UserEntityManager::class => \DI\autowire(),

    AccountRepository::class => \DI\autowire(),
    UserRepository::class => \DI\autowire(),

    View::class => \DI\autowire(),

    AccountValidatorInterface::class => \DI\autowire(AccountValidator::class),
    UserValidatorInterface::class => \DI\autowire(UserValidator::class),

    UserMapper::class => \DI\autowire(),
    AccountMapper::class => \DI\autowire(),

    ControllerProvider::class => \DI\autowire(),
    HomeController::class => \DI\autowire(),
    RegistrationController::class => \DI\autowire(),
    LoginController::class => \DI\autowire(),
    LogoutController::class => \DI\autowire(),
    ResetPasswordController::class => \DI\autowire(),
    ForgotPasswordController::class => \DI\autowire(),
];