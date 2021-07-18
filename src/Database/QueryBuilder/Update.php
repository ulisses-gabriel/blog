<?php

declare(strict_types=1);

namespace App\Database\QueryBuilder;

use App\Database\QueryBuilder\Filters\Where;

class Update extends AbstractQueryBuilder
{
    use Where;

    public function __construct(string $table, array $data, array $criteria = [])
    {
        unset($data['id']);

        $this->values = array_values($data);
        $this->query = $this->makeQuery($table, $data, $criteria);
    }

    private function makeQuery(string $table, array $data, array $criteria): string
    {
        $columns = array_keys($data);
        $columnsClause = [];

        foreach ($columns as $column) {
            $columnsClause[] = sprintf('%s = ?', $column); // name = ?
        }

        return sprintf(
            'UPDATE %s SET %s %s',
            $table,
            implode(',', $columnsClause),
            $this->where($criteria)
        );
    }
}