<?php /*declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use src\Model\EntityManager\UserEntityManager;

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

function isValidPass ($password) :string{
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/';

    if (!preg_match($pattern, $password)) {
        return "Password should be at least 6 characters long, and have special characters, numbers, capital and lower case letters.";
    }

    return '';
}

function isValidEmail ($email) :string {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    try {
        $json = file_get_contents("users.json");
        if (!$json) {
            return "";
        }
        $users = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        echo "JSON error: " . $e->getMessage();
    }
    foreach ($users as $user) {
        if ($email === $user['email']) {
            return "This email address is already in use.";
        }
    }

    return "";
}

if (isset($_POST["user"])) {
    $userName = $_POST["name"];
    $userEmail = $_POST['email'];
    $userPassword = $_POST['password'];

    $errors = [
        'email'=>isValidEmail($userEmail),
        'password'=>isValidPass($userPassword),
    ];


    if ($errors['email'] === '' && $errors['password'] === '') {
        $user = [
            "name" => $userName,
            "email" => $userEmail,
            "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
        ];

        try {
            $users = [];

            $json = file_get_contents("users.json");
            if ($json) {
                $users = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            }

            $users[] = $user;
            $data = json_encode($users, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
            file_put_contents("users.json", $data);

            header("Location: login.php");
            exit();

        } catch (JsonException $e) {
            echo "JSON error:" . $e->getMessage();
        }
    }
}

echo $twig->render('register.twig', [
    'userName' => $userName,
    'userEmail' => $userEmail,
    'errors' => $errors,
]);*/
