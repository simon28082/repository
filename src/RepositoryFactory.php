<?php

namespace CrCms\Repository;

use CrCms\Repository\Drivers\Eloquent\Eloquent;
use CrCms\Repository\Drivers\Eloquent\QueryRelate;
use CrCms\Repository\Drivers\RepositoryDriver;
use CrCms\Repository\Contracts\QueryRelate as QueryRelateContract;
use InvalidArgumentException;

/**
 * Class RepositoryFactory
 * @package CrCms\Repository
 */
class RepositoryFactory
{
    /**
     * @param string $driver
     * @param AbstractRepository $repository
     * @return RepositoryDriver
     */
    public static function driver(string $driver, AbstractRepository $repository): RepositoryDriver
    {
        switch ($driver) {
            case 'eloquent':
                return new Eloquent($repository);
            default:
                throw new InvalidArgumentException("Driver [{$driver}] not found");
        }
    }

    /**
     * @param string $driver
     * @param RepositoryDriver $repositoryDriver
     * @return QueryRelateContract
     */
    public static function query(string $driver, RepositoryDriver $repositoryDriver): QueryRelateContract
    {
        switch ($driver) {
            case 'eloquent':
                return new QueryRelate($repositoryDriver);
            default:
                throw new InvalidArgumentException("Driver [{$driver}] not found");
        }
    }
}