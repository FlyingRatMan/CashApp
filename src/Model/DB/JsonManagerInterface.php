<?php
declare(strict_types=1);

namespace App\Model\DB;

interface JsonManagerInterface
{
    public function read(): array;

    public function write(array $d): void;
}