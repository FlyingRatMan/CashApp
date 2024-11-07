<?php
declare(strict_types=1);

namespace App\Components\UserResetPassword\Business;

use App\Components\Token\Business\Model\TokenValidation;
use App\Components\User\Business\Model\UserValidation;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Core\View;

class UserResetPasswordFacade
{
    public function __construct(
        private View               $view,
        private UserBusinessFacade $userFacade,
        private TokenValidation    $tokenValidation,
        private UserValidation     $userValidation,
        private UserMapper         $userMapper
    ) {}

    public function resetPassword(string $token, string $newPassword): void
    {
        $email = hex2bin($token);

        $isValidToken = $this->tokenValidation->validateToken($email);
        $isValidUser = $this->userFacade->getUserByEmail($email);

        if (!$isValidToken || !$isValidUser) {
            $this->view->setRedirect('/index.php?page=forgotPassword'); // should be probably error page
            return;
        }

        $passwordErr = $this->userValidation->validatePassword($newPassword);

        if ($passwordErr === null) {
            $userDTO = $this->userMapper->createUserDTO(
                [
                    'id' => $isValidUser->id,
                    'name' => $isValidUser->name,
                    'email' => $isValidUser->email,
                    'password' => $isValidUser->password,
                ]
            );

            $this->userFacade->updatePassword($userDTO, $newPassword);
            $this->view->setRedirect('/index.php?page=login');
            return;
        }

        $this->view->addParameter('error', $passwordErr);
    }
}