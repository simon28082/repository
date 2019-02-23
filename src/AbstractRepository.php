<?php

namespace CrCms\Repository;

use CrCms\Event\HasEvents;
use UnexpectedValueException;
use Illuminate\Support\Collection;
use CrCms\Repository\Concerns\HasData;
use CrCms\Repository\Concerns\HasGuard;
use CrCms\Repository\Concerns\HasOriginal;
use CrCms\Repository\Contracts\QueryMagic;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Services\CacheService;
use CrCms\Repository\Concerns\HasSceneGuard;
use CrCms\Repository\Drivers\RepositoryDriver;
use CrCms\Repository\Exceptions\MethodNotFoundException;
use CrCms\Repository\Contracts\QueryRelate as QueryRelateContract;

/**
 * @method QueryRelateContract select(array $column = ['*'])
 * @method QueryRelateContract selectRaw(string $expression, array $bindings = [])
 * @method QueryRelateContract skip(int $limit)
 * @method QueryRelateContract take(int $limit)
 * @method QueryRelateContract groupBy(string $column)
 * @method QueryRelateContract groupByArray(array $columns)
 * @method QueryRelateContract orderBy(string $column, string $sort = 'desc')
 * @method QueryRelateContract orderByArray(array $columns)
 * @method QueryRelateContract distinct()
 * @method QueryRelateContract where(string $column, $operator = null, $value = null)
 * @method QueryRelateContract orWhere(string $column, $operator = null, $value = null)
 * @method QueryRelateContract whereClosure(\Closure $callback)
 * @method QueryRelateContract orWhereClosure(\Closure $callback)
 * @method QueryRelateContract whereBetween(string $column, array $between)
 * @method QueryRelateContract orWhereBetween(string $column, array $between)
 * @method QueryRelateContract whereRaw(string $sql, array $bindings = [])
 * @method QueryRelateContract orWhereRaw(string $sql, array $bindings = [])
 * @method QueryRelateContract orWhereNotBetween($column, array $between)
 * @method QueryRelateContract whereExists(\Closure $callback)
 * @method QueryRelateContract orWhereExists(\Closure $callback)
 * @method QueryRelateContract whereNotExists(\Closure $callback)
 * @method QueryRelateContract orWhereNotExists(\Closure $callback)
 * @method QueryRelateContract whereIn(string $column, array $values)
 * @method QueryRelateContract orWhereIn(string $column, array $values)
 * @method QueryRelateContract whereNotIn(string $column, array $values)
 * @method QueryRelateContract orWhereNotIn(string $column, array $values)
 * @method QueryRelateContract whereNull(string $column)
 * @method QueryRelateContract orWhereNull(string $column)
 * @method QueryRelateContract whereNotNull(string $column)
 * @method QueryRelateContract orWhereNotNull(string $column)
 * @method QueryRelateContract raw(string $sql)
 * @method QueryRelateContract from(string $table)
 * @method QueryRelateContract join(string $table, string $one, string $operator = '=', string $two = '')
 * @method QueryRelateContract joinClosure(string $table, \Closure $callback)
 * @method QueryRelateContract leftJoin(string $table, string $first, string $operator = '=', string $two = '')
 * @method QueryRelateContract leftJoinClosure(string $table, \Closure $callback)
 * @method QueryRelateContract rightJoin(string $table, string $first, string $operator = '=', string $two = '')
 * @method QueryRelateContract rightJoinClosure(string $table, \Closure $callback)
 * @method QueryRelateContract callable(callable $callable)
 * @method QueryRelateContract whereArray(array $array)
 * @method QueryRelateContract union(QueryRelateContract $queryRelate, bool $unionAll = true)
 * @method QueryRelateContract magic(QueryMagic $queryMagic)
 * @method QueryRelateContract whenMagic(?QueryMagic $queryMagic = null)
 * @method QueryRelateContract when(bool $condition, callable $trueCallable, callable $falseCallable)
 * @method QueryRelateContract whenMultiple(array $conditions, array $callables)
 * @method QueryRelateContract withArray(array $relations)
 * @method QueryRelateContract with(string $relation)
 * @method QueryRelateContract withoutArray(array $relations)
 * @method QueryRelateContract without(string $relation)
 * @method Collection all()
 * @method Collection get()
 * @method Collection pluck(string $column, string $key = '')
 * @method mixed first
 * @method mixed findByInt(int $id)
 * @method mixed findByString(string $id)
 * @method mixed oneByInt(string $field, int $value)
 * @method mixed oneByString(string $field, string $value)
 * @method mixed byStringId(string $id)
 * @method mixed byIntId(int $id)
 * @method int max(string $column)
 * @method int count(string $column = '*')
 * @method int avg($column)
 * @method int sum(string $column)
 * @method bool chunk(int $limit, callable $callback)
 * @method string valueOfString(string $key, string $default = '')
 * @method int valueOfInt(string $key, int $default = 0)
 * @method int increment(string $column, int $amount = 1, array $extra = [])
 * @method int decrement(string $column, int $amount = 1, array $extra = [])
 * @method int deleteByStringId(string $id)
 * @method int deleteByIntId(int $id)
 * @method int deleteByArray(array $ids): int
 *
 * Class AbstractRepository
 */
abstract class AbstractRepository
{
    use HasData, HasGuard, HasEvents, HasOriginal, HasSceneGuard;

    /**
     * @var Repository
     */
    protected $driver;

    /**
     * @var object
     */
    protected $model;

    /**
     * @var CacheService
     */
    protected $cache;

    /**
     * @var array
     */
    protected $config;

    /**
     * AbstractRepository constructor.
     */
    public function __construct()
    {
        $this->driver = $this->driver('eloquent');
    }

    /**
     * @return object
     */
    public function getModel()
    {
        if (! $this->model) {
            $this->model = $this->newModel();
        }

        return $this->model;
    }

    /**
     * @return object
     */
    abstract public function newModel();

    /**
     * @param array  $data
     * @param string $scene
     *
     * @return bool|object
     */
    public function create(array $data)
    {
        $scene = '';
        if (func_num_args() === 2) {
            $scene = func_get_arg(1);
        }

        $this->setOriginal($data);

        $this->setData($this->guardResult($data, $scene));

        if ($this->fireEvent('creating', $data) === false) {
            return false;
        }

        $model = $this->driver->create($this->getData());

        $this->fireEvent('created', $model);

        return $model;
    }

    /**
     * @param array      $data
     * @param string|int $id
     * @param string     $scene
     *
     * @return bool|object
     */
    public function update(array $data, $id)
    {
        $scene = '';
        if (func_num_args() === 3) {
            $scene = func_get_arg(2);
        }

        $this->setOriginal($data);

        $this->setData($this->guardResult($data, $scene));

        if ($this->fireEvent('updating', $data) === false) {
            return false;
        }

        $model = is_numeric($id) ?
            $this->driver->updateByIntId($this->getData(), $id) :
            $this->driver->updateByStringId($this->getData(), $id);

        $this->fireEvent('updated', $model);

        return $model;
    }

    /**
     * @param string|int $id
     *
     * @return int
     */
    public function delete($id)
    {
        $id = (array) $id;

        $this->setOriginal($id);

        $this->setData($id);

        if ($this->fireEvent('deleting', $id) === false) {
            return false;
        }

        $key = $this->getModel()->getKeyName();

        $models = $this->driver->whereIn($key, $this->getData())->get();

        $rows = $this->driver->whereIn($key, $this->getData())->delete();

        if ($rows <= 0) {
            throw new UnexpectedValueException('Data deletion failed, Keys is:'.implode(',', $this->data));
        }

        $this->fireEvent('deleted', $models);

        return $rows;
    }

    /**
     * @param string $driver
     *
     * @return RepositoryDriver
     */
    public function driver(string $driver): RepositoryDriver
    {
        return RepositoryFactory::driver($driver, $this);
    }

    /**
     * @param RepositoryDriver $repositoryDriver
     *
     * @return QueryRelateContract
     */
    public function newQueryRelate(RepositoryDriver $repositoryDriver): QueryRelateContract
    {
        return RepositoryFactory::query('eloquent', $repositoryDriver);
    }

    /**
     * @return RepositoryDriver
     */
    public function getDriver(): RepositoryDriver
    {
        return $this->driver;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return Repository|mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->driver, $name) || method_exists($this->driver->getQueryRelate(), $name)) {
            return $this->driver->$name(...$arguments);
        }

        throw new MethodNotFoundException(static::class, $name);
    }

    /**
     * @param array  $data
     * @param string $scene
     *
     * @return array
     */
    protected function guardResult(array $data, string $scene): array
    {
        // guard 优先
        return empty($this->guard) ?
            $this->sceneGuard($data, $scene ? $scene : $this->currentScene) :
            $this->guard($data);
    }

    /**
     * @param int $minute
     *
     * @return CacheService
     */
    public function cache(int $minute = 1440): CacheService
    {
        if (empty($this->cache)) {
            $this->cache = new CacheService();
            $this->cache->setRepository($this);
        }

        return $this->cache->setCacheMinute($minute);
    }
}
