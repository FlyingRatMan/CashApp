<?php
declare(strict_types=1);

namespace App\Components\UserForgetPassword\Business;

use App\Components\Mailer\Business\MailerFacade;
use App\Components\Mailer\Mapper\MailerMapper;
use App\Components\Token\Business\TokenFacade;
use App\Components\User\Business\UserBusinessFacade;

class UserForgetPasswordFacade
{
    public function __construct(
        private UserBusinessFacade $userFacade,
        private MailerFacade       $mailerFacade,
        private TokenFacade        $tokenFacade,
        private MailerMapper       $mailerMapper
    ) {}

    public function sendEmail(string $email): void
    {
        $user = $this->userFacade->getUserByEmail($email);

        if ($user !== null) {
            $resetLink = 'http://localhost:8080/index.php?page=resetPassword&token=' . bin2hex($email);

            $mailDTO = $this->mailerMapper->createMailDTO([
                    'from' => 'cash@cash.de',
                    'to' => $email,
                    'subject' => 'Reset Password',
                    'html' => '
                    <a href="' . $resetLink . '">Click here to reset password</a>
                    '
                ]
            );

            $this->mailerFacade->sendEmail($mailDTO);
            $this->saveToken($email);

            echo 'Password reset link was sent to your email :)';
        }
    }

    public function saveToken(string $email): void
    {
        $token = bin2hex($email);
        $existingToken = $this->tokenFacade->getTokenByEmail($email);

        if ($existingToken !== null) {
            // echo 'update';
            $tokenDTO = $this->tokenFacade->createTokenDTO(
                [
                    'id' => $existingToken->id,
                    'token' => $token,
                    'email' => $email,
                    'expires_at' => date('Y-m-d H:i:s', time() + 120 * 60)
                ]
            );
            $this->tokenFacade->updateToken($tokenDTO);
        }

        if ($existingToken === null) {
            // echo 'save';
            $tokenDTO = $this->tokenFacade->createTokenDTO(
                [
                    'id' => 1,
                    'token' => $token,
                    'email' => $email,
                    'expires_at' => date('Y-m-d H:i:s', time() + 120 * 60)
                ]
            );
            $this->tokenFacade->saveToken($tokenDTO);
        }
    }
}