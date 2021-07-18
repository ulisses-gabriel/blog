<?php

declare(strict_types=1);

namespace App\Database\QueryBuilder;

use App\Database\QueryBuilder\Filters\Where;

class Delete extends AbstractQueryBuilder
{
    use Where;

    public function __construct(string $table, array $criteria = [])
    {
        $this->query = $this->makeQuery($table, $criteria);
    }

    private function makeQuery(string $table, array $criteria): string
    {
        return sprintf('DELETE FROM %s %s', $table, $this->where($criteria));
    }
}