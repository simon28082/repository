<?php

namespace CrCms\Repository\Eloquent;

use CrCms\Repository\Contracts\Eloquent as EloquentContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class Eloquent implements EloquentContract
{
    /**
     * @return Model
     */
    abstract public function newModel();

    /**
     * @param array $columns
     *
     * @return Collection
     */
    public function all(array $columns = []): Collection
    {
        return $this->newQuery()->get(empty($columns) ? ['*'] : $columns);
    }

    /**
     * @param string $column
     * @param string|null $key
     *
     * @return Collection
     */
    public function pluck(string $column, ?string $key = null): Collection
    {
        return $this->newQuery()->pluck($column, $key);
    }

    /**
     * @param int $id
     *
     * @return Model|null
     */
    public function oneByIntId(int $id)
    {
        return $this->newQuery()->find($id);
    }

    /**
     * @param string $id
     *
     * @return Model|null
     */
    public function oneByStringId(string $id)
    {
        return $this->newQuery()->find($id);
    }

    /**
     * @param string $column
     * @param int $value
     *
     * @return Model|null
     */
    public function oneByInt(string $column, int $value)
    {
        return $this->newQuery()->where($column, $value)->first();
    }

    /**
     * @param string $column
     * @param string $value
     *
     * @return Model|null
     */
    public function oneByString(string $column, string $value)
    {
        return $this->newQuery()->where($column, $value)->first();
    }

    public function max(string $column): int
    {
        return $this->newQuery()->max($column);
    }

    public function count(string $column = '*'): int
    {
        // TODO: Implement count() method.
    }

    public function avg($column): int
    {
        // TODO: Implement avg() method.
    }

    public function sum(string $column): int
    {
        // TODO: Implement sum() method.
    }

    public function chunk(int $limit, callable $callback): bool
    {
        // TODO: Implement chunk() method.
    }

    public function increment(string $column, int $step = 1, array $extra = []): int
    {
        // TODO: Implement increment() method.
    }

    public function decrement(string $column, int $step = 1, array $extra = []): int
    {
        // TODO: Implement decrement() method.
    }

    public function deleteByIntId(int $id): int
    {
        // TODO: Implement deleteByIntId() method.
    }

    public function deleteByStringId(string $id): int
    {
        // TODO: Implement deleteByStringId() method.
    }

    public function paginate(array $columns = ['*'], $pageName = 'page', int $page = 0, int $perPage = 20): LengthAwarePaginator
    {
        // TODO: Implement paginate() method.
    }

    public function create(array $data): Model
    {
        // TODO: Implement create() method.
    }

    public function updateByIntId(array $data, int $id): Model
    {
        // TODO: Implement updateByIntId() method.
    }

    public function updateByStringId(array $data, string $id): Model
    {
        // TODO: Implement updateByStringId() method.
    }

    public function byIntIdOrFail(int $id): Model
    {
        // TODO: Implement byIntIdOrFail() method.
    }

    public function byStringIdOrFail(string $id): Model
    {
        // TODO: Implement byStringIdOrFail() method.
    }

    public function oneByStringOrFail(string $column, string $value): Model
    {
        // TODO: Implement oneByStringOrFail() method.
    }

    public function oneByIntOrFail(string $column, int $value): Model
    {
        // TODO: Implement oneByIntOrFail() method.
    }


    protected function newQuery(): Builder
    {
        return $this->newModel()->newQuery();
    }
}