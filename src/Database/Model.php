<?php

declare(strict_types=1);

namespace App\Database;

use App\Database\Adapters\PDOAdapterInterface;
use App\Database\Connection\Connection;
use App\Database\QueryBuilder\Delete;
use App\Database\QueryBuilder\Insert;
use App\Database\QueryBuilder\Paginate;
use App\Database\QueryBuilder\Select;
use App\Database\QueryBuilder\Update;

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
            $criteria[] = ['id', $id];
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
        $data = $this->pdoAdapter
            ->setQueryBuilder(new Select($this->getTable(), $criteria, $columns))
            ->execute()
            ->all();

        return $this->parseModels($data);
    }

    public function save(): Model
    {
        if ($this->id) {
            return $this->update();
        }

        $this->preSave();

        $this->created_at = date('Y-m-d H:i:s'); //Not all dbs support current timestamp
        $this->updated_at = date('Y-m-d H:i:s');

        $this->pdoAdapter
            ->setQueryBuilder(new Insert($this->getTable(), $this->data))
            ->execute();

        return $this->first($this->pdoAdapter->lastInsertedId());
    }

    public function update(): Model
    {
        if (!$this->id) {
            return $this->save();
        }

        $this->preSave();

        $this->updated_at = date('Y-m-d H:i:s'); //Not all dbs support current timestamp on update
        $criteria = [
            ['id', $this->id],
        ];

        $this->pdoAdapter
            ->setQueryBuilder(new Update($this->getTable(), $this->data, $criteria))
            ->execute();

        return $this;
    }

    public function delete(): bool
    {
        if (!$this->id) {
            return false;
        }

        $criteria = [
            ['id', $this->id],
        ];

        try {
            $this->pdoAdapter
                ->setQueryBuilder(new Delete($this->getTable(), $criteria))
                ->execute();
        } catch (\Throwable $exception) {
            return false;
        }

        return true;
    }

    public function findOneBy(array $criteria): ?Model
    {
        $data = $this->pdoAdapter
            ->setQueryBuilder(new Select($this->getTable(), $criteria))
            ->execute()
            ->first();

        $this->setData($data);

        return !empty($data) ? $this : null;
    }

    public function paginate(array $criteria = [], int $perPage = 3): Paginator
    {
        $page = (int)($_GET['page'] ?? 1);
        $data = $this->pdoAdapter
            ->setQueryBuilder(new Paginate($this->getTable(), $criteria, $page, $perPage))
            ->execute()
            ->all();
        $countForPagination = $this->pdoAdapter
            ->setQueryBuilder(new Select($this->getTable(), $criteria, ['count(*) as count']))
            ->execute()
            ->first();
        $itemsCount = (int)$countForPagination['count'] ?: 0;

        return (new Paginator($this->parseModels($data)))
            ->setCurrentPage($page)
            ->setPerPage($perPage)
            ->setPages((int)ceil($itemsCount / $perPage));
    }

    private function parseModels(array $modelsData): array
    {
        $models = [];

        foreach ($modelsData as $modelData) {
            $models[] = (new static())->setData($modelData)
                ->setPdoAdapter($this->pdoAdapter);
        }

        return $models;
    }
}