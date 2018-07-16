<?php

namespace CrCms\Repository\Drivers;

use CrCms\Repository\Contracts\QueryRelate as QueryRelateContract;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Exceptions\MethodNotFoundException;

/**
 * Class QueryRelate
 *
 * @package CrCms\Repository\Drivers
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
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->repository, $name)) {
            return call_user_func_array([$this->repository, $name], $arguments);
        }

        throw new MethodNotFoundException(static::class, $name);
    }
}