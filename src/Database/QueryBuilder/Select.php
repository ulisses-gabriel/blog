<?php

declare(strict_types=1);

namespace App\Database\QueryBuilder;

use App\Database\QueryBuilder\Filters\Where;

class Select extends AbstractQueryBuilder
{
    use Where;

    public function __construct(string $table, array $criteria, array $columns = ['*'])
    {
        $this->values = [];
        $this->query = $this->makeQuery($table, $criteria, $columns);
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