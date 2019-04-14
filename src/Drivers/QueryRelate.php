<?php

namespace CrCms\Repository\Drivers;

use CrCms\Repository\Contracts\QueryRelate as QueryRelateContract;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Exceptions\MethodNotFoundException;

/**
 * Class QueryRelate.
 */
abstract class QueryRelate implements QueryRelateContract
{
    /**
     * @var object
     */
    protected $query;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @param Repository $repository
     *
     * @return QueryRelateContract
     */
    public function setRepository(Repository $repository): QueryRelateContract
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return Repository
     */
    public function getRepository(): Repository
    {
        return $this->repository;
    }

    /**
     * @return object
     */
    public function getQuery(): object
    {
        return $this->query;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->repository, $name)) {
            return call_user_func_array([$this->repository, $name], $arguments);
        }

        throw new MethodNotFoundException(static::class, $name);
    }
}
