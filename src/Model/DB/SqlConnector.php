<?php
declare(strict_types=1);

namespace App\Model\DB;

use PDO;
use PDOException;

class SqlConnector
{
    private PDO $pdo;
    private static $connection = null;

    public function __construct()
    {
        $database = $_ENV['DATABASE'] ?? 'cash';
        $dsn = 'mysql:host=localhost:3336;dbname=' . $database;
        $user = 'root';
        $password = 'nexus123';

        try {
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getConnection(): SqlConnector
    {
        if (self::$connection === null) {
            self::$connection = new SqlConnector();
        }
        return self::$connection;
    }

    public function select(string $query, array $params = []): array
    {
        $stmt = $this->pdo->prepare($query);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value, PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function insert(string $query, array $params): bool
    {
        $stmt = $this->pdo->prepare($query);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value, PDO::PARAM_STR);
        }
        $stmt->execute();

        return (bool)$this->pdo->lastInsertId();
    }
}