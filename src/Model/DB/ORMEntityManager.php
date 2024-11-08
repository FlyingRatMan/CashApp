<?php
declare(strict_types=1);

namespace App\Model\DB;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMSetup;

class ORMEntityManager
{
    private static ?EntityManagerInterface $entityManager = null;

    public static function getEntityManager(): EntityManager
    {
        if (self::$entityManager === null) {
            $database = $_ENV['DATABASE'] ?? 'cash';

            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: array(__DIR__ . "/src"),
                isDevMode: true,
            );

            $connection = DriverManager::getConnection([
                'driver' => 'pdo_mysql',
                'host' => '127.0.0.1',
                'port' => 3336,
                'user' => 'root',
                'password' => 'nexus123',
                'dbname' => $database,
            ], $config);

            self::$entityManager = new EntityManager($connection, $config);
        }

        return self::$entityManager;
    }

    public static function getRepository(string $className): EntityRepository
    {
        return self::getEntityManager()->getRepository($className);
    }
}