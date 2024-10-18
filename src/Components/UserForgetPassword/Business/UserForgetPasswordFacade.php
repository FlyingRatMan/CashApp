<?php
declare(strict_types=1);

namespace App\Components\UserForgetPassword\Business;

use App\Components\Mailer\Business\MailerFacade;
use App\Components\Token\Business\TokenFacade;
use App\Components\User\Business\UserBusinessFacade;

class UserForgetPasswordFacade
{
    public function __construct(
        private UserBusinessFacade $userFacade,
        private MailerFacade       $mailerFacade,
        private TokenFacade        $tokenFacade,
    ) {}

    public function sendEmail(string $email): void
    {
        $user = $this->userFacade->getUserByEmail($email);

        if ($user !== null) {
            $this->mailerFacade->sendEmail($email);
        }

        $this->saveToken($email);
    }

    public function saveToken(string $email): void
    {
        $token = bin2hex($email);
        $tokenDTO = $this->tokenFacade->createTokenDTO(
            [
                'id' => 1,
                'token' => $token,
                'email' => $email,
                'expires_at' => date('Y-m-d H:i:s', time() + 120 * 60)
            ]
        );
        $existingToken = $this->tokenFacade->getTokenByEmail($email);

        if ($existingToken !== null) {
            echo 'update';
            $this->tokenFacade->updateToken($tokenDTO);
        }

        if ($existingToken === null) {
            echo 'save';
            $this->tokenFacade->saveToken($tokenDTO);
        }
    }
}