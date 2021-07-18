<?php

declare(strict_types=1);

namespace App\Database;

use App\Database\Adapters\PDOAdapterInterface;
use App\Database\Connection\Connection;
use App\Database\QueryBuilder\Select;

abstract class Model
{
    protected array $data;
    protected ?string $table;
    private ?PDOAdapterInterface $pdoAdapter;

    public function __construct()
    {
        $this->pdoAdapter = Connection::getConnectionAdapter();
        $this->table = null;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): Model
    {
        $this->data = $data;

        return $this;
    }

    public function getPdoAdapter(): ?PDOAdapterInterface
    {
        return $this->pdoAdapter;
    }

    public function setPdoAdapter(?PDOAdapterInterface $pdoAdapter): Model
    {
        $this->pdoAdapter = $pdoAdapter;

        return $this;
    }

    public function getTable(): string
    {
        if (!$this->table) {
            $class = explode('\\', get_class($this));

            $this->table = pluralize(strtolower(array_pop($class)));
        }

        return $this->table;
    }

    public function __get(string $name)
    {
        $method = $this->methodName('get', $name);

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return $this->data[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    private function methodName(string $prefix, string $name): string
    {
        return $prefix . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
    }

    public function toSql(): string
    {
        return $this->pdoAdapter->getSql();
    }

    protected function preSave(): void
    {
        //override on models if needed
    }

    public function first(int $id = null): ?Model
    {
        $criteria = [];

        if ($id) {
            $criteria[] = ['id' => $id];
        }

        $data = $this->pdoAdapter
            ->setQueryBuilder(new Select($this->getTable(), $criteria))
            ->execute()
            ->first();

        $this->setData($data);

        return !empty($data) ? $this : null;
    }

    public function all(array $criteria = [], array $columns = ['*']): array
    {
        return $this->pdoAdapter
            ->setQueryBuilder(new Select($this->getTable(), $criteria, $columns))
            ->execute()
            ->all();
    }
}