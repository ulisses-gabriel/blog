<?php

declare(strict_types=1);

namespace App\Database\Connection;

class MySQLConnection
{
    public function __invoke(): \PDO
    {
        $driver = Connection::MYSQL_CONNECTION;

        [
            'host' => $host,
            'dbname' => $dbname,
            'user' => $user,
            'password' => $password,
        ] = config('database.connections')[$driver];

        if (empty($host) || empty($dbname) || empty($user)) {
            throw new \InvalidArgumentException(sprintf('Invalid database credentials for driver [%s]', $driver));
        }

        return new \PDO("mysql:host={$host};dbname={$dbname}", $user, $password);
    }
}