<?php declare(strict_types=1);

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

        } catch (JsonException $e) {
            echo "JSON error:" . $e->getMessage();
        }
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
    <label for="name"> Name:
        <input required
               type="text"
               name="name"
               value="<?php if ($errors) { echo $userName; } ?>">
    </label>
    <label for="email"> E-Mail:
        <input required
               type="email"
               name="email"
               value="<?php if ($errors) { echo $userEmail; } ?>">
    </label>
    <label for="password"> Password:
        <input required
               type="password"
               name="password">
    </label>
    <p><?php if ($errors) {
            foreach ($errors as $error) {
                echo $error;
            }
        } ?></p>

    <input type="submit" name="user" value="Sign in!">
</form>

</body>
</html>
