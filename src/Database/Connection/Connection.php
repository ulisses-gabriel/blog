<?php

declare(strict_types=1);

namespace App\Database\Connection;

use App\Database\Adapters\MySQLAdapter;
use App\Database\Adapters\PDOAdapterInterface;
use App\Database\Adapters\SQLiteAdapter;

class Connection
{
    const MYSQL_CONNECTION = 'mysql';
    const SQLITE_CONNECTION = 'sqlite';

    private static array $connections = [
        self::MYSQL_CONNECTION => MySQLConnection::class,
        self::SQLITE_CONNECTION => SQLiteConnection::class,
    ];

    private static array $connectionAdapters = [
        self::MYSQL_CONNECTION => MySQLAdapter::class,
        self::SQLITE_CONNECTION => SQLiteAdapter::class,
    ];

    public static function getConnection(bool $connectToDB = true): \PDO
    {
        $driver = self::getAdapter();
        $connection = self::$connections[$driver] ?? null;

        if (!$connection) {
            throw new \Exception(sprintf('No config defined for driver [%s]', $driver));
        }

        return (new $connection())($connectToDB);
    }

    public static function getAdapter(): string
    {
        $driver = config('database.driver');

        if (!$driver) {
            throw new \Exception('No database driver defined.');
        }

        return $driver;
    }

    public static function getConnectionAdapter(\PDO $connection = null): PDOAdapterInterface
    {
        if (!$connection) {
            $connection = self::getConnection();
        }

        $adapter = self::getAdapter();
        $connectionAdapter = self::$connectionAdapters[$adapter];

        return new $connectionAdapter($connection);
    }
}