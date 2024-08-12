<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

class JsonManager
{
    public function __construct(
        private readonly string $pathToJson
    ) {}

    /**
     * @throws \JsonException
     */
    public function read(): array
    {
        if (!file_exists($this->pathToJson)) {
            return [];
        }

        $json = file_get_contents($this->pathToJson);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws \JsonException
     */
    public function write(array $data): void
    {
        file_put_contents($this->pathToJson, json_encode($data, JSON_THROW_ON_ERROR|JSON_PRETTY_PRINT));
    }
}