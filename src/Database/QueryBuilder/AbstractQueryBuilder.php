<?php

declare(strict_types=1);

namespace App\Database\QueryBuilder;

abstract class AbstractQueryBuilder implements QueryBuilderInterface
{
    protected array $values = [];
    protected string $query = '';

    public function getValues(): array
    {
        return $this->values;
    }

    public function __toString(): string
    {
        return $this->query;
    }
}