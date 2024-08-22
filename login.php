<?php
/*declare(strict_types=1);
session_start();
require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

function isUser(array $users, string $email, string $password) :bool {
    foreach ($users as $user) {
        if ($user["email"] === $email && password_verify($password, $user["password"])) {
            $_SESSION["loggedUser"] = $user["name"];
            return true;
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
        isUser($users, $userEmail, $userPassword);
    } catch (JsonException $e) {
        echo "JSON error: " . $e->getMessage();
    }

    header("Location: index.php");
    exit();
}

echo $twig->render('login.twig', [
    'submit' => isset($_POST["login"]),
]);*/
