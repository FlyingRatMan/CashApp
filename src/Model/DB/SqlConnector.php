<?php
declare(strict_types=1);

namespace App\Model\DB;

use PDO;
use PDOException;
use PDOStatement;

class SqlConnector
{
    private PDO $pdo;
    private static $connection = null;

    public function __construct()
    {
        $database = 'cash';
        $host = 'localhost:3336';
        $user = 'root';
        $password = 'nexus123';
        $dsn = 'mysql:host=' . $host . ';dbname=' . $database;

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
        $stmt->execute($params);

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