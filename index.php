<?php declare(strict_types=1);
session_start();

$successful = false;
$json = file_get_contents("account.json");
if (!$json)  {
    $_SESSION["kontostand"] = 0;
}
$loggedUser = $_SESSION['loggedUser'];

$today = date("Y-m-d 00:00:00");
$date = date("Y-m-d h:i:s");

$err = "";
function sanitize($input) :string {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    $input = preg_replace('/[^0-9,.]/', '', $input);
    return $input;
}

if (isset($_POST["submit"])) {
    $sanitizedInput = sanitize($_POST["amount"]);
    $onlyComas = str_replace(".", "", $sanitizedInput);
    $standardizedAmount = str_replace(",", ".", $onlyComas);
    $amount = (float)$standardizedAmount;

    $arr = explode(",", $onlyComas);
    if ($arr[1] && strlen($arr[1]) > 2) {
        $err = "Only two decimals are allowed";
    }

    if ($json) {
        try {
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo "JSON error:" . $e->getMessage();
        }
        $dailyLimit = 0;
        $hourlyLimit = 0;
        foreach ($data as $entry) {
            $diff = date_diff(date_create($today), date_create($entry["date"]));
            $hourDiff = date_create($entry["date"])->diff(date_create());
            if ($diff->d === 0) {
                $dailyLimit += $entry["amount"];
            }
            if ($hourDiff->format("%H") === "00") {
                $hourlyLimit += $entry["amount"];
            }
        }
        $hourlyLimit += $amount;
        $dailyLimit += $amount;

        if ($dailyLimit > 500 || (int)$amount > 500) {
            $err = "Limit is exceeded";
        }
        if ( $hourlyLimit > 100) {
            $err = "Limit is exceeded";
        }
    }

    if (!$err) {
        $transaction = [
            "amount" => $amount,
            "date" => $date,
        ];

        try {
            $transactions = [];

            if ($json) {
                $transactions = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            }

            $transactions[] = $transaction;
            $data = json_encode($transactions, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
            file_put_contents("account.json", $data);

            $_SESSION["kontostand"] += $amount;

        } catch (JsonException $e) {
            echo "JSON error:" . $e->getMessage();
        }
    }
}

if (isset($_POST["logout"])) {
    unset($_SESSION["loggedUser"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>CashApp</title>
</head>
<body>

<h2><?php echo $loggedUser ?></h2>
<form method="POST">
<?php
if ($loggedUser) {
    echo '<input type="submit" name="logout" value="Log out">';
}
if (!$loggedUser) {
    echo '<input type="submit" name="login" value="Log in">';
}
?>
</form>

<h3>Kontostand: <?php echo $_SESSION["kontostand"] ?></h3>
<h2>Geld hochladen</h2>

<form method="POST">

    <label for="amount"> Betrag:
        <input required
               type="text"
               name="amount"
               value="<?php if ($err !== "") { echo $amount; } ?>">
    </label> <br>

    <input type="submit" name="submit" value="Hochladen">
    <p><?php echo $err ?></p>
    <p><?php if (isset($_POST["submit"]) && $_SESSION["kontostand"]) {
            echo "Die Transaktion wurde erfolgreich gespeichert!"; } ?></p>

</form>

</body>
</html>



