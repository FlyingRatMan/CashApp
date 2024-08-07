<?php declare(strict_types=1);
session_start();

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

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form method="POST">
    <label for="email"> E-Mail:
        <input required
               type="email"
               name="email">
    </label>
    <label for="password"> Password:
        <input required
               type="password"
               name="password">
    </label>

    <input type="submit" name="login" value="Log in!">
</form>

</body>
</html>
