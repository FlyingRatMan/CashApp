<?php declare(strict_types=1);
session_start();

$successful = false;
$json = file_get_contents("account.json");
if (!$json) $_SESSION["kontostand"] = 0;

// VALIDATION
if (isset($_POST["submit"])) {
    $amount =
}

// DATA MANIPULATION
if (isset($_POST["submit"])) {
    $date = date("Y-m-d");
    $time = date("h:i:s");

    $transaction = [
        "amount" => $_POST["amount"],
        "date" => $date,
        "time" => $time,
    ];

    if ($json) {
        $data = json_decode($json, true);
        $data[] = $transaction;
        $file = json_encode($data, JSON_PRETTY_PRINT);
    } else {
        $file = json_encode([$transaction], JSON_PRETTY_PRINT);
    }
    $_SESSION["kontostand"] += $_POST["amount"];
    file_put_contents("account.json", $file);

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
    Betrag: <input type="number" step="any" name="amount"><br>

    <input type="submit" name="submit" value="Hochladen">
</form>

</body>
</html>

