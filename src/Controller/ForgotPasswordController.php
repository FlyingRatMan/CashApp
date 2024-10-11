<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\DB\SqlConnector;
use App\Model\User\UserRepository;

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

                // send an email here

                $db = $this->sqlConnector::getConnection();
                $query = "INSERT INTO Reset_password_tokens (token, email, expires_at) 
                            VALUES (:token, :email, :expires_at)";

                $params = [
                    'token' => $token,
                    'email' => $_POST['email'],
                    'expires_at' => date('Y-m-d H:i:s', time() + 120 * 60),
                ];

                $db->insert($query, $params);
            }
        }

        $this->view->setTemplate('forgotPassword.twig');
    }
}