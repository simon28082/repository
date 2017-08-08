<?php

namespace CrCms\Repository;

use CrCms\Event\HasEvents;
use CrCms\Repository\Concerns\HasData;
use CrCms\Repository\Concerns\HasGuard;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Drives\Eloquent\Eloquent;
use CrCms\Repository\Exceptions\MethodNotFoundException;

/**
 * Class AbstractRepository
 * @package CrCms\Repository
 */
abstract class AbstractRepository
{
    use HasData, HasGuard, HasEvents;

    /**
     * @var Repository|null
     */
    protected $driver = null;

    /**
     * @var object|null
     */
    protected $model = null;

    /**
     * @var array
     */
    protected static $events = [];

    /**
     * AbstractRepository constructor.
     */
    public function __construct()
    {
        $this->driver = $this->driver();

        //$this->registerDefaultEvents();
    }

    /**
     * @return object|null
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
        $this->setData($this->guard($data));

        if ($this->fireEvent('creating') === false) return false;

        $model = $this->driver->create($this->getData());

        $this->fireEvent('created', $model);

        return $model;
    }

    /**
     * @param array $data
     * @param string|int $id
     * @return null
     */
    public function update(array $data, $id)
    {
        $this->setData($this->guard($data));

        if ($this->fireEvent('updating') === false) return false;

        $model = $this->driver->update($this->getData(), $id);

        $this->fireEvent('updated', $model);

        return $model;
    }

    /**
     * @param string|int $id
     * @return int
     */
    public function delete($id)
    {
        $this->setData((array)$id);

        if ($this->fireEvent('deleting') === false) return false;

        $models = $this->driver->whereIn('id',$this->getData())->get();

        $rows = $this->driver->delete($this->getData(),$models);

        $this->fireEvent('deleted', $rows);

        return $rows;
    }

    /**
     * @return void
     */
    protected function registerDefaultEvents()
    {
        $listeners = array_merge_recursive(config('repository.listen'), static::$events);
        foreach ($listeners as $event => $listener) {
            static::registerEvent($event, $listener);
        }
    }

    /**
     * @return Repository
     */
    public function driver(): Repository
    {
        return (new Eloquent($this));
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
}