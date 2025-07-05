<?php

declare(strict_types=1);

namespace App\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;

class Database
{
    private static ?Connection $conn = null;

    public static function init(array $params): void
    {
        if (self::$conn === null) {
            self::$conn = DriverManager::getConnection([
                'dbname'   => $params['dbname'],
                'user'     => $params['user'],
                'password' => $params['password'],
                'host'     => $params['host'],
                'port'     => $params['port'],
                'driver'   => 'pdo_mysql',
            ]);
        }
    }

    public static function getConnection(): Connection
    {
        if (self::$conn === null) {
            throw new \RuntimeException('Database connection not initialized.');
        }

        return self::$conn;
    }
}
