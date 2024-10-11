<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\DB\SqlConnector;
use App\Model\User\UserDTO;
use App\Model\User\UserEntityManager;
use App\Model\User\UserRepository;
use App\Service\UserValidator;

class ResetPasswordController
{
    public function __construct(
        private View              $view,
        private SqlConnector      $sqlConnector,
        private UserEntityManager $userEntityManager,
        private UserRepository    $userRepository,
        private UserValidator     $userValidator,
    ) {}

    public function index(): void
    {
        $token = hex2bin($_GET['token']);
        $isValidToken = $this->tokenValidation($token);
        $isValidUser = $this->userRepository->getUserByEmail(hex2bin($token));

        if ($isValidToken && $isValidUser) {
            $this->view->setTemplate('resetPassword.twig');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $passwordErr = $this->userValidator->isValidPassword($_POST['password']);

            if (empty($passwordErr)) {
                $userDTO = new UserDTO($isValidUser->id, $isValidUser->name, $isValidUser->email, $isValidUser->password);
                $this->userEntityManager->updatePassword($userDTO, password_hash($_POST['password'], PASSWORD_DEFAULT));
            }

            $this->view->addParameter('error', $passwordErr);
        }
    }

    public function tokenValidation(string $email): bool
    {
        $db = $this->sqlConnector::getConnection();
        $query = 'SELECT * FROM Reset_password_tokens WHERE email = :email LIMIT 1';
        $data = $db->select($query, ['email' => $email]);

        if ($data) {
            $expiry = $data[0]['expires_at'];
            $now = date('Y-m-d h:i:s');

            if ($expiry < $now) {
                return true;
            }
        }

        return false;
    }
}