<?php

declare(strict_types=1);

namespace App\Database\Adapters;

use App\Database\Connection\Connection;
use App\Database\QueryBuilder\QueryBuilderInterface;

class MySQLAdapter implements PDOAdapterInterface
{
    protected ?\PDO $pdo;
    protected QueryBuilderInterface $queryBuilder;
    protected \PDOStatement $statement;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function connect(): PDOAdapterInterface
    {
        $this->pdo = Connection::getConnection();

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $this;
    }

    public function close(): void
    {
        $this->pdo = null;
    }

    public function setQueryBuilder(QueryBuilderInterface $queryBuilder): PDOAdapterInterface
    {
        $this->queryBuilder = $queryBuilder;

        return $this;
    }

    public function getSql(): string
    {
        return (string)$this->queryBuilder;
    }

    public function execute(): PDOAdapterInterface
    {
        $this->statement = $this->pdo->prepare((string)$this->queryBuilder);
        $this->statement->execute($this->queryBuilder->getValues());

        return $this;
    }

    public function lastInsertedId(): int
    {
        return (int)$this->pdo->lastInsertId();
    }

    public function first(): array
    {
        return $this->statement->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    public function all(): array
    {
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }
}