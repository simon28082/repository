<?php

namespace CrCms\Repository\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface RepositoryInterface
 * @package CrCms\Repository\Contract
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
     * @param int $id
     * @param array $columns
     * @return Collection
     */
    public function byId(int $id, array $columns = ['*']) : Collection;


    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return Collection
     */
    public function oneBy(string $field, string $value, array $columns = ['*']) : Collection;


    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return Collection
     */
    public function by(string $field, string $value, array $columns = ['*']) : Collection;


    /**
     * @param QueryMagic $queryMagic
     * @return Repository
     */
    public function byQueryMagic(QueryMagic $queryMagic) : Repository;


    /**
     * @param callable $callable
     * @return Repository
     */
    public function byQueryCallback(callable $callable) : Repository;
}