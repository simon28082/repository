<?php
namespace CrCms\Repository\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface Repository
 * @package CrCms\Repository\Contracts
 */
interface Repository
{
    /**
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']) : Collection;


    /**
     * @param int $perPage
     * @param array $columns
     * @return Collection
     */
    public function paginate(int $perPage = 15, array $columns = ['*']) : Collection;


    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return Collection
     */
    public function by(string $field, string $value, array $columns = ['*']) : Collection;


    /**
     * @param callable $callable
     * @return Repository
     */
    public function byCallable(callable $callable) : Repository;


    /**
     * @param array $wheres
     * @param array $columns
     * @return Collection
     */
    public function byWhere(array $wheres, array $columns = ['*']) : Collection;
}