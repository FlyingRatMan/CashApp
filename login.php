<?php declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';
session_start();

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

function isUser($users, $email, $password) :string|bool {
    foreach ($users as $user) {
        if ($user["email"] === $email && password_verify($password, $user["password"])) {
            return $user['name'];
        }
    }
    return false;
}

if (isset($_POST["login"])) {
    $userEmail = $_POST["email"];
    $userPassword = $_POST["password"];

    try {
        $json = file_get_contents('users.json');
        $users = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        echo "JSON error: " . $e->getMessage();
    }

    $_SESSION["loggedUser"] = isUser($users, $userEmail, $userPassword);
}

echo $twig->render('login.twig', [
    'submit' => isset($_POST["login"]),
]);
