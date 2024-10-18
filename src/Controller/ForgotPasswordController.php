<?php
/*declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\DB\SqlConnector;
use App\Model\User\UserRepository;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\Transports;
use Symfony\Component\Mime\Email;

class ForgotPasswordController
{
    public function __construct(
        private View           $view,
        private SqlConnector   $sqlConnector,
        private UserRepository $userRepository,
    ) {}

    public function index(): void
    {
        if (isset($_POST['sendEmail'])) {
            $user = $this->userRepository->getUserByEmail($_POST['email']);

            if ($user) {
                $token = bin2hex($_POST['email']);
                $resetLink = 'http://localhost:8080/index.php?page=resetPassword&token=' . $token;
                $mailer = new Mailer(new Transports(['main' => new EsmtpTransport('localhost', 1025)]));

                $mail = (new Email())
                    ->from('cash@cash.app.de')
                    ->to($user->email)
                    ->subject('Reset Password')
                    ->html('
                    <a href="' . $resetLink . '">Click here to reset password</a>
                    ');

                $mailer->send($mail);

                $db = $this->sqlConnector::getConnection();
                $tokenQuery = 'SELECT * FROM Reset_password_tokens WHERE email = :email LIMIT 1';

                $existingToken = $db->select($tokenQuery, ['email' => $_POST['email']]);

                if (!empty($existingToken)) {
                    $updateQuery = "UPDATE Reset_password_tokens SET token = :token, email = :email, expires_at = :expires_at 
                             WHERE id = :id";
                    $params = [
                        'token' => $token,
                        'email' => $_POST['email'],
                        'expires_at' => date('Y-m-d H:i:s', time() + 120 * 60),
                        'id' => $existingToken[0]['id'],
                    ];

                    $db->update($updateQuery, $params);
                }

                if (empty($existingToken)) {
                    $insertQuery = "INSERT INTO Reset_password_tokens (token, email, expires_at) 
                            VALUES (:token, :email, :expires_at)";
                    $params = [
                        'token' => $token,
                        'email' => $_POST['email'],
                        'expires_at' => date('Y-m-d H:i:s', time() + 120 * 60),
                    ];

                    $db->insert($insertQuery, $params);
                }
            }
        }

        $this->view->setTemplate('forgotPassword.twig');
    }
}*/