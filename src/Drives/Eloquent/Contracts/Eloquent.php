<?php

namespace CrCms\Repository\Drives\Eloquent\Contracts;

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

    /**
     * @param array $data
     * @param string $id
     * @return Model
     */
    public function updateByStringId(array $data, string $id) : Model;

    /**
     * @param int $id
     * @return Model|null
     */
    public function byIntId(int $id);

    /**
     * @param string $id
     * @return Model|null
     */
    public function byStringId(string $id);

    /**
     * @param int $id
     * @return Model
     */
    public function byIntIdOrFail(int $id) : Model;

    /**
     * @param string $id
     * @return Model
     */
    public function byStringIdOrFail(string $id) : Model;

    /**
     * @param string $field
     * @param string $value
     * @return Model
     */
    public function oneByString(string $field, string $value): Model;

    /**
     * @param string $field
     * @param int $value
     * @return Model
     */
    public function oneByInt(string $field, int $value): Model;

    /**
     * @param string $field
     * @param string $value
     * @return Model
     */
    public function oneByStringOrFail(string $field, string $value) : Model;

    /**
     * @param string $field
     * @param int $value
     * @return Model
     */
    public function oneByIntOrFail(string $field, int $value) : Model;

    /**
     * @return Model|null
     */
    public function first();

    /**
     * @return Model
     */
    public function firstOrFail() : Model;
}