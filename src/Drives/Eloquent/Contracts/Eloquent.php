<?php
namespace CrCms\Repository\Drives\Eloquent\Contracts;

use CrCms\Repository\Exceptions\ResourceNotFoundException;
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
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15) : LengthAwarePaginator;


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
    public function updateByIntId(array $data, int $id) : Model;


    /*
     *
     */
    public function updateByStringId(array $data,string $id) : Model;


    /**
     * @param int $id
     * @return Model or null
     */
    public function byIntId(int $id) : Model;


    /**
     * @param string $id
     * @return Model or null
     */
    public function byStringId(string $id) : Model;


//    /**
//     * @param int $id
//     * @throws ResourceNotFoundException
//     * @return Model
//     */
//    public function byIntIdOrFail(int $id) : Model;
//
//
//    /**
//     * @param string $id
//     * @throws ResourceNotFoundException
//     * @return Model
//     */
//    public function byStringIdOrFail(string $id) : Model;


    /**
     * @param string $field
     * @param string $value
     * @return Model or null
     */
    public function oneByString(string $field, string $value): Model;


    /**
     * @param string $field
     * @param int $value
     * @return Model or null
     */
    public function oneByInt(string $field, int $value): Model;


    /**
     * @param string $field
     * @param string $value
     * @throws ResourceNotFoundException
     * @return Model
     */
    //public function oneByStringOrFail(string $field, string $value) : Model;


    /**
     * @param string $field
     * @param int $value
     * @throws ResourceNotFoundException
     * @return Model
     */
    //public function oneByIntOrFail(string $field, int $value) : Model;


    /**
     * @return Model or null
     */
    public function first() : Model;


    /**
     * @throws ResourceNotFoundException
     * @return Model
     */
    //public function firstOrFail() : Model;

}