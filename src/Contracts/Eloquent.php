<?php
declare(strict_types = 1);

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
     * @param null $key
     *
     * @return Model
     */
    public function update(array $data, $key = null): Model;

    /**
     * @param string|int $key
     *
     * @return Model
     */
    public function byKeyOrFail(string $key): Model;

    /**
     * @param string $column
     * @param string|int $value
     *
     * @return Model
     */
    public function oneByOrFail(string $column, $value): Model;
}
