<?php

namespace CrCms\Repository;

use CrCms\Event\HasEvents;
use CrCms\Repository\Concerns\HasData;
use CrCms\Repository\Concerns\HasGuard;
use CrCms\Repository\Concerns\HasOriginal;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Drives\Eloquent\Eloquent;
use CrCms\Repository\Drives\RepositoryDriver;
use CrCms\Repository\Exceptions\MethodNotFoundException;
use CrCms\Repository\Services\CacheService;
use UnexpectedValueException;

/**
 * Class AbstractRepository
 * @package CrCms\Repository
 */
abstract class AbstractRepository
{
    use HasData, HasGuard, HasEvents, HasOriginal;

    /**
     * @var Repository
     */
    protected $driver;

    /**
     * @var object
     */
    protected $model;

    /**
     * @var array
     */
    protected static $events = [];

    /**
     * @var CacheService
     */
    protected $cache;

    /**
     * AbstractRepository constructor.
     */
    public function __construct()
    {
        $this->driver = $this->driver();
    }

    /**
     * @return object
     */
    public function getModel()
    {
        if (!$this->model) {
            $this->model = $this->newModel();
        }

        return $this->model;
    }

    /**
     * @return object
     */
    abstract public function newModel();

    /**
     * @param array $data
     * @return bool|mixed
     */
    public function create(array $data)
    {
        $this->setOriginal($data);

        $this->setData($this->guard($data));

        if ($this->fireEvent('creating', $data) === false) return false;

        $model = $this->driver->create($this->getData());

        $this->fireEvent('created', $model);

        return $model;
    }

    /**
     * @param array $data
     * @param string|int $id
     * @return mixed
     */
    public function update(array $data, $id)
    {
        $this->setOriginal($data);

        $this->setData($this->guard($data));

        if ($this->fireEvent('updating', $data) === false) return false;

        $model = is_numeric($id) ?
            $this->driver->updateByIntId($this->getData(), $id) :
            $this->driver->updateByStringId($this->getData(), $id);

        $this->fireEvent('updated', $model);

        return $model;
    }

    /**
     * @param string|int $id
     * @return int
     */
    public function delete($id)
    {
        $id = (array)$id;

        $this->setOriginal($id);

        $this->setData($id);

        if ($this->fireEvent('deleting', $id) === false) return false;

        $key = $this->getModel()->getKeyName();

        $models = $this->driver->whereIn($key, $this->getData())->get();

        $rows = $this->driver->whereIn($key, $this->getData())->delete();

        if ($rows <= 0) {
            throw new UnexpectedValueException('Data deletion failed, Keys is:' . implode(',', $this->data));
        }

        $this->fireEvent('deleted', $models);

        return $rows;
    }

    /**
     * @return RepositoryDriver
     */
    public function driver(string $driver = null): RepositoryDriver
    {
        if (empty($driver)) {
            return (new Eloquent($this));
        }

        return new $driver($this);
    }

    /**
     * @return RepositoryDriver
     */
    public function getDriver(): RepositoryDriver
    {
        return $this->driver;
    }

    /**
     * @return array
     */
    public static function events(): array
    {
        return array_merge(
            array_keys(config('repository.listen')),
            array_keys(static::$events)
        );
    }

    /**
     * @param string $name
     * @param array $arguments
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
     * @param int $minute
     * @return CacheService
     */
    public function cache(int $minute = 1440): CacheService
    {
        if (empty($this->cache)) {
            $this->cache = new CacheService;
            $this->cache->setRepository($this);
        }

        return $this->cache->setCacheMinute($minute);
    }
}