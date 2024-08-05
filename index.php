<?php declare(strict_types=1);
session_start();

$successful = false;
$json = file_get_contents("account.json");
if (!$json) $_SESSION["kontostand"] = 0;

// VALIDATION
$amount = 0;
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
    $amount = floatval($standardizedAmount);

    $arr = explode(",", $onlyComas);
    if ($arr[1] && strlen($arr[1]) > 2) {
        $err = "Only two decimals are allowed";
    }
    if ((int)$amount > 500) {
        $err = "Limit is excited";
    }
    if ($json) {
        $data = json_decode($json, true);
        $dailyLimit = 0;
        foreach ($data as $entry) {
            $diff = date_diff(date_create($entry["date"]), date_create($today));
            if ($diff->d === 0) {
                $dailyLimit += $entry["amount"];
            }
            if ($diff->h) {
var_dump($diff->m);
            }
        }
        if ($dailyLimit > 500) {
            $err = "Limit is excited";
        }
    }



    $transaction = [
        "amount" => $amount,
        "date" => $date,
    ];

    if (!$err) {
        if ($json) {
            $data = json_decode($json, true);
            $data[] = $transaction;
            $file = json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $file = json_encode([$transaction], JSON_PRETTY_PRINT);
        }
        $_SESSION["kontostand"] += $amount;
        file_put_contents("account.json", $file);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>CashApp</title>
</head>
<body>

<p> <?php if (isset($_POST["submit"]) && $_SESSION["kontostand"]) echo "Die Transaktion wurde erfolgreich gespeichert!" ?> </p>
<h3>Kontostand: <?php echo $_SESSION["kontostand"] ?></h3>

<h2>Geld hochladen</h2>
<form action="topup" method="POST">
    Betrag: <input required type="text" step="any" name="amount" > <br>

    <input type="submit" name="submit" value="Hochladen">
    <p><?php echo $err ?></p>
</form>

</body>
</html>

