<?php
namespace CrCms\Repository\Contracts;


interface Eloquent
{
    /**
     * @param array $data
     * @return Collection
     */
    public function create(array $data) : Model;


    /**
     * @param array $data
     * @param int $id
     * @return Collection
     */
    public function update(array $data, int $id) : Collection;


    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id) : int;
}