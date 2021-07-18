<?php

declare(strict_types=1);

namespace App\Database\QueryBuilder\Filters;

trait Where
{
    public function where(array $criteria): string
    {
        if (empty($criteria)) {
            return '';
        }

        $where = [];
        $values = [];

        foreach ($criteria as $condition) {
            $field = $condition[0];
            $value = $condition[1];
            $operator = '=';

            if (!empty($condition[2])) {
                $operator = $value;
                $values = $condition[2];
            }

            $where[] = sprintf('%s %s ?', $field, $operator); // field = ?
            $values[] = $value;
        }

        $this->values = array_merge($this->values, $values);

        return sprintf(' WHERE %s ', implode(' AND ', $where));
    }
}