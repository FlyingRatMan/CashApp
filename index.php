<?php
declare(strict_types=1);
session_start();

use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\RegistrationController;
use App\Model\EntityManager\AccountEntityManager;
use App\Model\EntityManager\JsonManager;
use App\Model\EntityManager\UserEntityManager;
use App\Model\Repository\AccountRepository;
use App\Model\Repository\UserRepository;
use App\Service\AccountValidator;
use App\Service\UserValidator;

require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$userJson = new JsonManager('users.json');
$accountJson = new JsonManager('account.json');

$accRepo = new AccountRepository($accountJson);
$accEntity = new AccountEntityManager($accountJson);
$accValid = new AccountValidator();
$userRepo = new UserRepository($userJson);
$userEntity = new UserEntityManager($userJson);
$userValid = new UserValidator();

$controller = $_GET['page'] ?? 'home';
$method = $_SERVER['REQUEST_METHOD'];

$controllerInit = match ($controller) {
    'login' => new LoginController($twig),
    'register' => new RegistrationController($twig, $userEntity, $userRepo, $userValid),
    'logout' => new LogoutController($twig),
    'home' => new HomeController($twig, $accEntity, $accRepo, $accValid),
};

$controllerInit->index();

if ($method === 'POST' && $controller === 'home') {
    $controllerInit->transfer();
}
if ($method === 'POST' && $controller === 'register') {
    $controllerInit->register();
}

//  !!!old code
/*$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$successful = false;
$json = file_get_contents("account.json");
if (!$json)  {
    $_SESSION["kontostand"] = 0;
}
$loggedUser = $_SESSION['loggedUser'];
$kontostand = $_SESSION['kontostand'] ?? '0';

$today = date("Y-m-d 00:00:00");
$date = date("Y-m-d h:i:s");

$err = "";
function sanitize(string $input) :string {
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
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_POST["login"])) {
    header("Location: login.php");
    exit();
}

echo $twig->render('index.twig', [
    'loggedUser' => $loggedUser,
    'kontostand' => $kontostand,
    'amount' => $err !== "" ? $amount : "",
    'err' => $err,
    'submit' => isset($_POST["submit"])
]);*/
