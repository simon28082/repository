<?php

namespace CrCms\Repository\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface Eloquent extends Database
{
    /**
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param int $page
     *
     * @return LengthAwarePaginator
     */
    public function paginate(array $columns = ['*'], string $pageName = 'page', int $page = 0, int $perPage = 20): LengthAwarePaginator;

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * @param array $data
     * @param int $id
     *
     * @return Model
     */
    public function updateByIntId(array $data, int $id): Model;

    /**
     * @param array $data
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
     * @param string $column
     * @param string $value
     *
     * @return Model
     */
    public function oneByStringOrFail(string $column, string $value): Model;

    /**
     * @param string $column
     * @param int $value
     *
     * @return Model
     */
    public function oneByIntOrFail(string $column, int $value): Model;
}
