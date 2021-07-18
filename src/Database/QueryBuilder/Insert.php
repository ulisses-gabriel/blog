<?php

declare(strict_types=1);

namespace App\Database\QueryBuilder;

class Insert extends AbstractQueryBuilder
{
    public function __construct(string $table, array $data)
    {
        $this->values = array_values($data);
        $this->query = $this->makeQuery($table, $data);
    }

    private function makeQuery(string $table, array $data): string
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Can not insert without data');
        }

        return sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(',', array_keys($data)),
            implode(',', array_fill(0, count($data), '?')) // VALUES (?, ?, ?...)
        );
    }
}