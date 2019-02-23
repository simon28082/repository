<?php

namespace CrCms\Repository\Drivers\Eloquent\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface Eloquent.
 */
interface Eloquent
{
    /**
     * @param int    $perPage
     * @param array  $columns
     * @param string $pageName
     * @param null   $page
     *
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], $pageName = 'page', int $page = 0): LengthAwarePaginator;

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * @param array $data
     * @param int   $id
     *
     * @return Model
     */
    public function updateByIntId(array $data, int $id): Model;

    /**
     * @param array  $data
     * @param string $id
     *
     * @return Model
     */
    public function updateByStringId(array $data, string $id): Model;

    /**
     * @param int $id
     *
     * @return Model
     */
    public function byIntIdOrFail(int $id): Model;

    /**
     * @param string $id
     *
     * @return Model
     */
    public function byStringIdOrFail(string $id): Model;

    /**
     * @param string $field
     * @param string $value
     *
     * @return Model
     */
    public function oneByStringOrFail(string $field, string $value): Model;

    /**
     * @param string $field
     * @param int    $value
     *
     * @return Model
     */
    public function oneByIntOrFail(string $field, int $value): Model;

    /**
     * @return Model
     */
    public function firstOrFail(): Model;

    /**
     * @param int $id
     *
     * @return Model
     */
    public function findByIntOrFail(int $id): Model;

    /**
     * @param string $id
     *
     * @return Model
     */
    public function findByStringOrFail(string $id): Model;
}
