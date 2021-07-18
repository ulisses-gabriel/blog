<?php

declare(strict_types=1);

namespace App\Database\QueryBuilder;

interface QueryBuilderInterface
{
    public function getValues(): array;

    public function __toString(): string;
}