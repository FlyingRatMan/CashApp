<?php
declare(strict_types=1);

namespace App\Components\Account;

use Error;

class AccountValidation
{
    public function limit(array $data, int $amount): ?Error
    {
        $dailyLimit = 0;
        $hourlyLimit = 0;

        $today = date("Y-m-d 00:00:00");

        foreach ($data as $accountDTO) {
            $diff = date_diff(date_create($today), date_create($accountDTO->date));
            $hourDiff = date_create($accountDTO->date)->diff(date_create());
            if ($diff->d === 0) {
                $dailyLimit += $accountDTO->amount;
            }
            if ($hourDiff->format("%H") === "00") {
                $hourlyLimit += $accountDTO->amount;
            }
        }
        $hourlyLimit += $amount;
        $dailyLimit += $amount;

        if ($dailyLimit > 500) {
            return new Error('Daily limit of 500 is exceeded.');
        }
        if ( $hourlyLimit > 100) {
            return new Error('Hourly limit of 100 is exceeded.');
        }

        return null;
    }

    public function isValidAmount(string $amount): ?Error
    {
        $amount = $this->transform($amount);
        $arr = explode(".", $amount);

        if ((count($arr) > 1) && (strlen($arr[1]) > 2)) {
            return new Error("Only two decimals are allowed");
        }

        return null;
    }

    private function sanitize(string $input): string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return preg_replace('/[^0-9,.]/', '', $input);
    }

    public function transform(string $input): string
    {
        $sanitizedInput = $this->sanitize($input);
        return str_replace([".", ","], ["", "."], $sanitizedInput);
    }
}