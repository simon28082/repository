<?php

namespace CrCms\Repository\Eloquent;

use CrCms\Repository\Concerns\Data;
use CrCms\Repository\Concerns\Event;
use CrCms\Repository\Concerns\Original;
use CrCms\Repository\Concerns\Scene;
use CrCms\Repository\Contracts\Eloquent as EloquentContract;
use CrCms\Repository\Exceptions\ResourceDeleteException;
use CrCms\Repository\Exceptions\ResourceNotFoundException;
use CrCms\Repository\Exceptions\ResourceStoreException;
use CrCms\Repository\Exceptions\ResourceUpdateException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

abstract class Eloquent implements EloquentContract
{
    use Scene,Data,Original,Event;

    /**
     * @var Model
     */
    protected $model;

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
        return $this->proxyReset(
            $this->model()->get(empty($columns) ? ['*'] : $columns)
        );
    }

    /**
     * @param string $column
     * @param string|null $key
     *
     * @return Collection
     */
    public function pluck(string $column, ?string $key = null): Collection
    {
        return $this->proxyReset(
            $this->model()->pluck($column, $key)
        );
    }

    /**
     * @param int $id
     *
     * @return Model|null
     */
    public function oneByIntId(int $id)
    {
        return $this->proxyReset(
            $this->model()->find($id)
        );
    }

    /**
     * @param string $id
     *
     * @return Model|null
     */
    public function oneByStringId(string $id)
    {
        return $this->proxyReset(
            $this->model()->find($id)
        );
    }

    /**
     * @param string $column
     * @param int $value
     *
     * @return Model|null
     */
    public function oneByInt(string $column, int $value)
    {
        return $this->proxyReset(
            $this->model()->where($column, $value)->first()
        );
    }

    /**
     * @param string $column
     * @param string $value
     *
     * @return Model|null
     */
    public function oneByString(string $column, string $value)
    {
        return $this->proxyReset(
            $this->model()->where($column, $value)->first()
        );
    }

    public function chunk(int $limit, callable $callback): bool
    {
        return $this->proxyReset(
            $this->model()->chunk($limit, $callback)
        );
    }

    public function increment(string $column, int $step = 1, array $extra = []): int
    {
        return $this->proxyReset(
            $this->model()->increment($column, $step, $extra)
        );
    }

    public function decrement(string $column, int $step = 1, array $extra = []): int
    {
        return $this->proxyReset(
            $this->model()->decrement($column, $step, $extra)
        );
    }

    public function paginate(array $columns = ['*'], string $pageName = 'page', int $page = 0, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model()->paginate($perPage, $columns, $pageName, $page);
    }

    public function deleteByIntId(int $id): int
    {
        try {
            return $this->newModel()::destroy($id);
        } catch (\Exception $e) {
            throw new ResourceDeleteException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteByStringId(string $id): int
    {
        try {
            return $this->newModel()::destroy($id);
        } catch (\Exception $e) {
            throw new ResourceDeleteException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function create(array $data,?string $scene = null): Model
    {
        $this->setOriginal($data);

        $this->setData($this->sceneFilter($data));

        if ($this->fireRepositoryEvent('creating') === false) {
            return false;
        }

        try {
            $model = $this->newModel()->guard([])->create($this->getData());

            $this->fireRepositoryEvent('created',$model);

        } catch (\Exception $e) {// @todo 暂时全部Exception
            throw new ResourceStoreException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function updateByIntId(array $data, int $id): Model
    {
        return $this->update($data,$id);
    }

    public function updateByStringId(array $data, string $id): Model
    {
        return $this->update($data,$id);
    }

    public function update(array $data,$key = null): Model
    {
        $key = $key ?? $this->model()->getKeyName();

        if (empty($keyValue = $data[$key])) {
            throw new \UnexpectedValueException("primary key not found");
        }

        try {
            return $this->newModel()->where($key,$keyValue)->update($data);
        } catch (\Exception $e) {
            throw new ResourceUpdateException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function delete()
    {

    }

    public function byIntIdOrFail(int $id): Model
    {
        try {
            return $this->model()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function byStringIdOrFail(string $id): Model
    {
        try {
            return $this->model()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function oneByStringOrFail(string $column, string $value): Model
    {
        try {
            return $this->model()->where($column, $value)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function oneByIntOrFail(string $column, int $value): Model
    {
        try {
            return $this->model()->where($column, $value)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    protected function model(): Model
    {
        if (is_null($this->model)) {
            $this->model = $this->newModel();
        }

        return $this->model;
    }

    protected function resetModel(): void
    {
        $this->model = $this->newModel();
    }

    protected function proxyReset($result)
    {
        return tap($result, function () {
            $this->resetModel();
        });
    }
}