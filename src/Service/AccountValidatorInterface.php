<?php
declare(strict_types=1);

namespace App\Service;

interface AccountValidatorInterface
{
    public function limit(array $data, int $amount): string;

    public function isValidAmount(string $amount): string;

    public function sanitize(string $input): string;

    public function transform(string $input): string;
}