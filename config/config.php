<?php
declare(strict_types=1);

use App\Components\Account\Communication\AccountController;
use App\Components\Account\Persistence\AccountEntityManager;
use App\Components\Account\Persistence\AccountRepository;
use App\Components\Account\Persistence\Mapper\AccountMapper;
use App\Components\Token\Persistence\Mapper\TokenMapper;
use App\Components\Token\Persistence\TokenEntityManager;
use App\Components\Token\Persistence\TokenRepository;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserForgetPassword\Business\UserForgetPasswordFacade;
use App\Components\UserForgetPassword\Communication\UserForgetPasswordController;
use App\Components\UserLogin\Communication\UserLoginController;
use App\Components\UserRegister\Communication\UserRegisterController;
use App\Components\UserResetPassword\Communication\UserResetPasswordController;

use App\Controller\LogoutController;
use App\Core\View;
use App\Model\DB\SqlConnector;
use App\Service\ControllerProvider;
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
    TokenEntityManager::class => \DI\autowire(),

    AccountRepository::class => \DI\autowire(),
    UserRepository::class => \DI\autowire(),
    TokenRepository::class => \DI\autowire(),

    View::class => \DI\autowire(),

    // AccountValidatorInterface::class => \DI\autowire(AccountValidator::class),
    // UserValidatorInterface::class => \DI\autowire(UserValidator::class),

    UserMapper::class => \DI\autowire(),
    AccountMapper::class => \DI\autowire(),
    TokenMapper::class => \DI\autowire(),

    UserForgetPasswordFacade::class => \DI\autowire(),

    ControllerProvider::class => \DI\autowire(),
    AccountController::class => \DI\autowire(),
    UserRegisterController::class => \DI\autowire(),
    UserLoginController::class => \DI\autowire(),
    LogoutController::class => \DI\autowire(),
    UserResetPasswordController::class => \DI\autowire(),
    UserForgetPasswordController::class => \DI\autowire(),
];