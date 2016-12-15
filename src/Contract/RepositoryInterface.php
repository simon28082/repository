<?php

namespace CrCms\Repository\Contract;

use Illuminate\Support\Collection;

/**
 * Interface RepositoryInterface
 * @package CrCms\Repository\Contract
 */
interface RepositoryInterface
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
     * @param array $data
     * @return BaseModelInterface
     */
    public function create(array $data) : BaseModelInterface;


    /**
     * @param array $data
     * @param int $id
     * @return BaseModelInterface
     */
    public function update(array $data, int $id) : BaseModelInterface;


    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id) : int;


    /**
     * @param int $id
     * @param array $columns
     * @return Collection
     */
    public function findById(int $id, array $columns = ['*']) : Collection;


    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return Collection
     */
    public function findOneBy(string $field, string $value, array $columns = ['*']) : Collection;


    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return Collection
     */
    public function findBy(string $field, string $value, array $columns = ['*']) : Collection;
}