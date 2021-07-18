<?php

declare(strict_types=1);

namespace App\Database\Connection;

class SQLiteConnection
{
    public function __invoke(): \PDO
    {
        $driver = Connection::SQLITE_CONNECTION;
        $dbname = config('database.connections')[$driver]['dbname'] ?? null;

        if (!$dbname) {
            throw new \InvalidArgumentException(sprintf('No dbname defined for driver [%s]', $driver));
        }

        $filepath = __DIR__ . sprintf('/%s.sqlite', $dbname); //lets save the db file inside the connections folder

        return new \PDO(sprintf('sqlite:%s', $filepath));
    }
}