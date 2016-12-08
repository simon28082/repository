<?php

namespace CrCms\Repository\Repositories;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = ['*']);


    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function findAllPaginate(int $perPage = 15, array $columns = ['*']);


    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data) : Model;


    /**
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data,int $id) : Model;


    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id) : int;


    /**
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function findById(int $id, array $columns = ['*']);


    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return mixed
     */
    public function findOneBy(string $field,string $value,array $columns = ['*']);


    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return mixed
     */
    public function findBy(string $field,string $value,array $columns = ['*']);
}