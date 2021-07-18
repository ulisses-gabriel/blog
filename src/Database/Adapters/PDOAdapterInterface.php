<?php

declare(strict_types=1);

namespace App\Database\Adapters;

use App\Database\QueryBuilder\QueryBuilderInterface;

interface PDOAdapterInterface
{
    public function connect(): PDOAdapterInterface;

    public function close(): void;

    public function setQueryBuilder(QueryBuilderInterface $queryBuilder): PDOAdapterInterface;

    public function execute(): PDOAdapterInterface;

    public function lastInsertedId(): int;

    public function first(): array;

    public function all(): array;

    public function getSql(): string;
}