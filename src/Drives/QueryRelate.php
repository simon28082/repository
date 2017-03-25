<?php
namespace CrCms\Repository\Drives;

use CrCms\Repository\Contracts\QueryRelate as QueryRelateContract;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Exceptions\MethodNotFoundException;

abstract class QueryRelate implements QueryRelateContract
{

    protected $query = null;

    protected $repository = null;


    public function setRepository(Repository $repository) : QueryRelateContract
    {
        $this->repository = $repository;
        return $this;
    }

    public function getRepository() : Repository
    {
        return $this->repository;
    }


    public function __call($name, $arguments)
    {
        if (method_exists($this->repository,$name)) {
            return call_user_func_array([$this->repository,$name],$arguments);
        }

        throw new MethodNotFoundException(static::class,$name);
    }

}