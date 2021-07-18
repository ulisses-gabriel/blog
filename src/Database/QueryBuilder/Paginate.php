<?php

declare(strict_types=1);

namespace App\Database\QueryBuilder;

use App\Database\QueryBuilder\Filters\Where;

class Paginate extends AbstractQueryBuilder
{
    use Where;

    public function __construct(string $table, array $criteria = [], int $page = 1, int $perPage = 3)
    {
        $this->query = $this->makeQuery($table, $criteria, $page, $perPage);
    }

    private function makeQuery(string $table, array $criteria, int $page, int $perPage): string
    {
        $page -= 1; //offset starts at 0

        return sprintf(
            'SELECT * FROM %s %s ORDER BY created_at DESC LIMIT %s OFFSET %s',
            $table,
            $this->where($criteria),
            $perPage,
            $perPage * $page
        );
    }
}