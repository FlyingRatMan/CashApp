<?php
declare(strict_types=1);

namespace App\Model\Account;

readonly class AccountDTO
{
    public function __construct(
        public float $amount,
        public string $date,
    ) {}

    public function getAmount(): float
    {
        return $this->amount;
    }
    public function getDate(): string
    {
        return $this->date;
    }
}