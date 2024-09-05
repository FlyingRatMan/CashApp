<?php
declare(strict_types=1);

namespace App\Service;

class AccountValidator implements AccountValidatorInterface
{
    public function limit(array $data, int $amount): string
    {
        $dailyLimit = 0;
        $hourlyLimit = 0;

        $today = date("Y-m-d 00:00:00");

        foreach ($data as $accountDTO) {
            $diff = date_diff(date_create($today), date_create($accountDTO->getDate()));
            $hourDiff = date_create($accountDTO->getDate())->diff(date_create());
            if ($diff->d === 0) {
                $dailyLimit += $accountDTO->getAmount();
            }
            if ($hourDiff->format("%H") === "00") {
                $hourlyLimit += $accountDTO->getAmount();
            }
        }
        $hourlyLimit += $amount;
        $dailyLimit += $amount;

        if ($dailyLimit > 500) {
            return 'Daily limit of 500 is exceeded.';
        }
        if ( $hourlyLimit > 100) {
            return 'Hourly limit of 100 is exceeded.';
        }

        return '';
    }

    public function isValidAmount(string $amount): string
    {
        $amount = $this->transform($amount);
        $arr = explode(".", $amount);

        if ((count($arr) > 1) && (strlen($arr[1]) > 2)) {
            return "Only two decimals are allowed";
        }

        return '';
    }

    public function sanitize(string $input): string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return preg_replace('/[^0-9,.]/', '', $input);

    }

    public function transform(string $input): string
    {
        $sanitizedInput = $this->sanitize($input);
        $onlyComas = str_replace(".", "", $sanitizedInput);
        return str_replace(",", ".", $onlyComas);
    }
}