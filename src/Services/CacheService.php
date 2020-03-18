<?php

namespace CrCms\Repository\Services;

use CrCms\Repository\AbstractRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Traits\ForwardsCalls;

/**
 * Class Cache.
 *
 * @author simon
 */
class CacheService
{
    use ForwardsCalls;

    /**
     * @var AbstractRepository
     */
    protected $repository;

    /**
     * @var array
     */
    protected $cacheKeys = [];

    /**
     * @var int
     */
    protected $cacheMinute = 1440;

    /**
     * @param AbstractRepository $repository
     *
     * @return $this
     */
    public function setRepository(AbstractRepository $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @param int $minute
     *
     * @return $this
     */
    public function setCacheMinute(int $minute): self
    {
        $this->cacheMinute = $minute;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function forget(string $key): void
    {
        $cacheKey = $this->cacheKeys[$key] ?? null;
        if (!empty($cacheKey)) {
            Cache::forget($cacheKey);
        }
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        array_map(function ($cacheKey) {
            Cache::forget($cacheKey);
        }, $this->cacheKeys);
    }

    /**
     * @param string $cacheKey
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    protected function remember(string $cacheKey, string $name, array $arguments)
    {
        $cache = Cache::get($cacheKey);
        if ($cache) {
            return $cache;
        }

        $result = call_user_func_array([$this->repository, $name], $arguments);
        $driver = $this->repository->getDriver();
        if ($result instanceof $driver) {
            return $result;
        }

        if (!is_null($result)) {
            Cache::put($cacheKey, $result, $this->cacheMinute);
        }

        return $result;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function cachePrefix(string $name): string
    {
        return get_class($this->repository).$name;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return string
     */
    protected function setKey(string $name, array $arguments): string
    {
        $prefix = $this->cachePrefix($name);
        $cacheKey = sha1($prefix.serialize($arguments));
        $this->cacheKeys[$prefix] = $cacheKey;

        return $cacheKey;
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $cacheKey = $this->setKey($name, $arguments);
        if (method_exists($this->repository, $name)) {
            return $this->remember($cacheKey, $name, $arguments);
        }

        static::throwBadMethodCallException($name);
    }
}
