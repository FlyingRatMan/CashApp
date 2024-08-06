<?php declare(strict_types=1);
session_start();

$json = file_get_contents("users.json");

$userName = "";
$userEmail = "";
$userPassword = "";
function sanitize($input) :string {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

if (isset($_POST['submit'])) {
    $user = [
            "name" => $_POST['name'],
            "email" => $_POST['email'],
            "password" => password_hash($_POST['password'], PASSWORD_BCRYPT),
    ];

    if ($json) {
        // validation before saving
        $data = json_decode($json, true);
        $data[] = $user;
        $file = json_encode($data, JSON_PRETTY_PRINT);
    } else {
        $file = json_encode([$user], JSON_PRETTY_PRINT);
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign in</title>
</head>
<body>

<form method="POST">
    <input required type="text" name="name">
    <input required type="text" name="email">
    <input required type="text" name="password">

    <input type="submit">
</form>

</body>
</html>
