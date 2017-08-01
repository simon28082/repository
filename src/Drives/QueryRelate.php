<?php

namespace CrCms\Repository\Drives;

use CrCms\Repository\Contracts\QueryRelate as QueryRelateContract;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Exceptions\MethodNotFoundException;

/**
 * Class QueryRelate
 *
 * @package CrCms\Repository\Drives
 */
abstract class QueryRelate implements QueryRelateContract
{
    /**
     * @var null
     */
    protected $query = null;

    /**
     * @var null
     */
    protected $repository = null;

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