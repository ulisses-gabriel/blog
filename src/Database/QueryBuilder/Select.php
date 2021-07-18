<?php

declare(strict_types=1);

namespace App\Database\QueryBuilder;

use App\Database\QueryBuilder\Filters\Where;

class Select implements QueryBuilderInterface
{
    use Where;

    private array $values;
    private string $query;

    public function __construct(string $table, array $criteria, array $columns = ['*'])
    {
        $this->values = [];
        $this->query = $this->makeQuery($table, $criteria, $columns);
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function __toString(): string
    {
        return $this->query;
    }

    private function makeQuery(string $table, array $criteria, array $columns): string
    {
        return sprintf(
            'SELECT %s FROM %s %s',
            implode(',', $columns),
            $table,
            $this->where($criteria)
        );
    }
}