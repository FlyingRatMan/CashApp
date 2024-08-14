<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Service\UserValidator;

require __DIR__ . '/../../../vendor/autoload.php';

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
        $json && $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        return $data ?? [];
    }

    public function write(array $d): void
    {
        try {
            $data = [];

            if (file_exists($this->pathToJson)) {
                $json = file_get_contents($this->pathToJson);

                if ($json) {
                    $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
                }
            }

            $data[] = $d;

            $data = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
            file_put_contents($this->pathToJson, $data);
        } catch (\JsonException $e) {
            echo "JSON error:" . $e->getMessage();
        }
    }
}