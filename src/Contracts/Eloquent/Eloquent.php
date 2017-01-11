<?php
namespace CrCms\Repository\Contracts\Eloquent;

use CrCms\Repository\Contracts\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface Eloquent
 * @package CrCms\Repository\Contracts\Eloquent
 */
interface Eloquent
{

    /**
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*']) : LengthAwarePaginator;



    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data) : Model;


    /**
     * @param array $data
     * @param int $id
     * @return Model
     */
    public function update(array $data, int $id) : Model;


    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id) : int;


    /**
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function byId(int $id, array $columns = ['*']) : Model;


    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return Model
     */
    public function oneBy(string $field, string $value, array $columns = ['*']) : Model;


    /**
     * @param QueryMagic $queryMagic
     * @return Repository
     */
    public function byMagic(QueryMagic $queryMagic) : Repository;

}