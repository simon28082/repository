<?php

namespace CrCms\Repository\Drivers\Eloquent;

use CrCms\Repository\AbstractRepository;
use CrCms\Repository\Contracts\QueryRelate;
use CrCms\Repository\Drivers\Eloquent\Contracts\Eloquent as EloquentContract;
use CrCms\Repository\Drivers\RepositoryDriver;
use CrCms\Repository\Exceptions\MethodNotFoundException;
use CrCms\Repository\Exceptions\ResourceDeleteException;
use CrCms\Repository\Exceptions\ResourceNotFoundException;
use CrCms\Repository\Exceptions\ResourceStoreException;
use CrCms\Repository\Exceptions\ResourceUpdateException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Eloquent.
 */
class Eloquent extends RepositoryDriver implements EloquentContract
{
    /**
     * Eloquent constructor.
     *
     * @param AbstractRepository $repository
     */
    public function __construct(AbstractRepository $repository)
    {
        parent::__construct($repository);

        $this->setQueryRelate($this->newQueryRelate());
    }

    /**
     * @return void
     */
    public function resetQueryRelate()
    {
        $this->setQueryRelate($this->newQueryRelate());
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    protected function byIdOrFail($id)
    {
        $model = $this->byId($id);
        if (empty($model)) {
            throw new ResourceNotFoundException();
        }

        return $model;
    }

    /**
     * @param int $id
     *
     * @return Model
     */
    public function byIntIdOrFail(int $id): Model
    {
        return $this->byIdOrFail($id);
    }

    /**
     * @param string $id
     *
     * @return Model
     */
    public function byStringIdOrFail(string $id): Model
    {
        return $this->byIdOrFail($id);
    }

    /**
     * @return Builder
     */
    public function newQuery(): Builder
    {
        return $this->repository->getModel()->newQuery();
    }

    /**
     * @return QueryRelate
     */
    public function newQueryRelate(): QueryRelate
    {
        return $this->repository->newQueryRelate($this)->setQuery($this->newQuery());
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data): Model
    {
        try {
            $model = $this->repository->newModel()->create($data);

            return $model;
        } catch (\RuntimeException $exception) {
            throw new ResourceStoreException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function update(array $data): int
    {
        $row = $this->queryRelate->getQuery()->update($data);

        $this->resetQueryRelate();

        return $row;
    }

    /**
     * @param Model $model
     * @param array $data
     *
     * @return Model
     */
    protected function updateByModel(Model $model, array $data): Model
    {
        array_walk($data, function ($value, $key) use ($model) {
            $model->{$key} = $value;
        });

        try {
            $model->save();
        } catch (\RuntimeException $exception) {
            throw new ResourceUpdateException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return $model;
    }

    /**
     * @param array $data
     * @param int   $id
     *
     * @return Model
     */
    public function updateByIntId(array $data, int $id): Model
    {
        $model = $this->byIntIdOrFail($id);

        return $this->updateByModel($model, $data);
    }

    /**
     * @param array  $data
     * @param string $id
     *
     * @return Model
     */
    public function updateByStringId(array $data, string $id): Model
    {
        $model = $this->byStringIdOrFail($id);

        return $this->updateByModel($model, $data);
    }

    /**
     * @param int $perPage
     *
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], $pageName = 'page', int $page = 0): LengthAwarePaginator
    {
        $paginate = $this->queryRelate->getQuery()->paginate($perPage, $columns, $pageName, $page);

        $this->resetQueryRelate();

        return $paginate;
    }

    /**
     * @param $id
     *
     * @return Model
     */
    protected function byId($id)
    {
        //在QueryRelate 中的 __call中也没有找到first，需要用getQuery()，它指向的是Query\Builder
        $model = $this->queryRelate->where($this->repository->getModel()->getKeyName(), $id)->getQuery()->first();

        $this->resetQueryRelate();

        return $model;
    }

    /**
     * @param int $id
     *
     * @return Model
     */
    public function byIntId(int $id)
    {
        return $this->byId($id);
    }

    /**
     * @param string $id
     *
     * @return Model
     */
    public function byStringId(string $id)
    {
        return $this->byId($id);
    }

    /**
     * @param string $field
     * @param string $value
     *
     * @return Model
     */
    public function oneByString(string $field, string $value)
    {
        return $this->oneBy($field, $value);
    }

    /**
     * @param string $field
     * @param int    $value
     *
     * @return Model
     */
    public function oneByInt(string $field, int $value)
    {
        return $this->oneBy($field, $value);
    }

    /**
     * @param string $field
     * @param string $value
     *
     * @return Model
     */
    public function oneByStringOrFail(string $field, string $value): Model
    {
        return $this->oneByOrFail($field, $value);
    }

    /**
     * @param string $field
     * @param int    $value
     *
     * @return Model
     */
    public function oneByIntOrFail(string $field, int $value): Model
    {
        return $this->oneByOrFail($field, $value);
    }

    /**
     * @param string $field
     * @param $value
     *
     * @return Model|null
     */
    public function oneBy(string $field, $value)
    {
        $model = $this->queryRelate->where($field, $value)->getQuery()->first();

        $this->resetQueryRelate();

        return $model;
    }

    /**
     * @param string $field
     * @param $value
     *
     * @return Model
     */
    public function oneByOrFail(string $field, $value): Model
    {
        $model = $this->oneBy($field, $value);
        if (empty($model)) {
            throw new ResourceNotFoundException();
        }

        return $model;
    }

    /**
     * @return Model|null
     */
    public function first()
    {
        $model = $this->queryRelate->getQuery()->first();

        $this->resetQueryRelate();

        return $model;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        //all表示不加任何条件，必须重新设置query
        $models = $this->queryRelate->setQuery($this->newQuery())->getQuery()->get();

        $this->resetQueryRelate();

        return $models;
    }

    /**
     * @return Collection
     */
    public function get(): Collection
    {
        $models = $this->queryRelate->getQuery()->get();

        $this->resetQueryRelate();

        return $models;
    }

    /**
     * @param string      $column
     * @param string|null $key
     *
     * @return Collection
     */
    public function pluck(string $column, string $key = null): Collection
    {
        $models = $this->queryRelate->getQuery()->pluck($column, $key);

        $this->resetQueryRelate();

        return $models;
    }

    /**
     * @param mixed $id
     *
     * @return int
     */
    public function delete(): int
    {
        $rows = 0;

        try {
            $rows = $this->queryRelate->getQuery()->delete();
        } catch (\RuntimeException $exception) {
            throw new ResourceDeleteException($exception->getMessage(), $exception->getCode(), $exception);
        } finally {
            $this->resetQueryRelate();
        }

        return $rows;
    }

    /**
     * @param $id
     * @param string|null $key
     *
     * @return int
     */
    protected function deleteByKey($id, string $key = null): int
    {
        $key = empty($key) ? $this->repository->getModel()->getKeyName() : $key;

        $row = 0;

        try {
            $row = $this->queryRelate->whereIn($key, (array) $id)->getQuery()->delete();
        } catch (\RuntimeException $exception) {
            throw new ResourceDeleteException($exception->getMessage(), $exception->getCode(), $exception);
        } finally {
            $this->resetQueryRelate();
        }

        return $row;
    }

    /**
     * @param string $id
     *
     * @return int
     */
    public function deleteByStringId(string $id, string $key = null): int
    {
        return $this->deleteByKey($id, $key);
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function deleteByIntId(int $id, string $key = null): int
    {
        return $this->deleteByKey($id, $key);
    }

    /**
     * @param array $ids
     *
     * @return int
     */
    public function deleteByArray(array $ids, string $key = null): int
    {
        return $this->deleteByKey($ids, $key);
    }

    /**
     * @param string $column
     *
     * @return int
     */
    public function max(string $column): int
    {
        $max = $this->queryRelate->getQuery()->max($column);

        $this->resetQueryRelate();

        return $max;
    }

    /**
     * @param string $column
     *
     * @return int
     */
    public function count(string $column = '*'): int
    {
        $count = $this->queryRelate->getQuery()->count($column);

        $this->resetQueryRelate();

        return $count;
    }

    /**
     * @param $column
     *
     * @return int
     */
    public function avg($column): int
    {
        $avg = $this->queryRelate->getQuery()->avg($column);

        $this->resetQueryRelate();

        return $avg;
    }

    /**
     * @param string $column
     *
     * @return int
     */
    public function sum(string $column): int
    {
        $sum = $this->queryRelate->getQuery()->sum($column);

        $this->resetQueryRelate();

        return $sum;
    }

    /**
     * @param int      $limit
     * @param callable $callback
     *
     * @return bool
     */
    public function chunk(int $limit, callable $callback): bool
    {
        $result = $this->queryRelate->getQuery()->chunk($limit, $callback);

        $this->resetQueryRelate();

        return $result;
    }

    /**
     * @param string $key
     * @param string $default
     *
     * @return string
     */
    public function valueOfString(string $key, string $default = ''): string
    {
        return $this->value($key, $default);
    }

    /**
     * @param string $key
     * @param int    $default
     *
     * @return int
     */
    public function valueOfInt(string $key, int $default = 0): int
    {
        return $this->value($key, $default);
    }

    /**
     * @param string $key
     * @param $default
     *
     * @return mixed
     */
    protected function value(string $key, $default)
    {
        $value = $this->queryRelate->getQuery()->value($key);

        $this->resetQueryRelate();

        if (empty($value)) {
            $value = $default;
        }

        return $value;
    }

    /**
     * @param string $column
     * @param int    $amount
     * @param array  $extra
     *
     * @return int
     */
    public function increment(string $column, int $amount = 1, array $extra = []): int
    {
        $rows = $this->queryRelate->getQuery()->increment($column, $amount, $extra);
        $this->resetQueryRelate();

        return $rows;
    }

    /**
     * @param string $column
     * @param int    $amount
     * @param array  $extra
     *
     * @return int
     */
    public function decrement(string $column, int $amount = 1, array $extra = []): int
    {
        $rows = $this->queryRelate->getQuery()->decrement($column, $amount, $extra);
        $this->resetQueryRelate();

        return $rows;
    }

    /**
     * @return Model
     */
    public function firstOrFail(): Model
    {
        $model = $this->first();
        if (empty($model)) {
            throw new ResourceNotFoundException();
        }

        return $model;
    }

    /**
     * return run sql.
     *
     * @return string
     */
    public function toSql(): string
    {
        $sql = $this->queryRelate->getQuery()->toSql();

        $this->resetQueryRelate();

        return $sql;
    }

    /**
     * @param int $id
     *
     * @return Model
     */
    public function findByInt(int $id)
    {
        return $this->getRepository()->getModel()->find($id);
    }

    /**
     * @param int $id
     *
     * @return Model
     */
    public function findByIntOrFail(int $id): Model
    {
        $model = $this->findByInt($id);
        if (empty($model)) {
            throw new ResourceNotFoundException();
        }

        return $model;
    }

    /**
     * @param string $id
     *
     * @return Model
     */
    public function findByString(string $id)
    {
        return $this->getRepository()->getModel()->find($id);
    }

    /**
     * @param string $id
     *
     * @return Model
     */
    public function findByStringOrFail(string $id): Model
    {
        $model = $this->findByString($id);
        if (empty($model)) {
            throw new ResourceNotFoundException();
        }

        return $model;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return $this|mixed
     */
    public function __call($name, $arguments)
    {
        //$forbidden = ['paginate','get','all','first','update','create','delete','find','value','pluck','chunk','sum','avg','count','max','min','increment','decrement'];
        //&& !in_array($name,$forbidden,true)
        if (method_exists($this->queryRelate, $name)) {
            $result = call_user_func_array([$this->queryRelate, $name], $arguments);
            if ($result instanceof $this->queryRelate) {
                $this->setQueryRelate($result);

                return $this;
            }

            return $result;
        }

        throw new MethodNotFoundException(static::class, $name);
    }
}
