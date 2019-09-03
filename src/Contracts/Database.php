<?php

namespace CrCms\Repository\Contracts;

use Illuminate\Support\Collection;

interface Database extends Any
{
    /**
     * @param array $columns
     *
     * @return Collection
     */
    public function all(array $columns = []): Collection;

    /**
     * @param string $column
     * @param string|null $key
     *
     * @return Collection
     */
    public function pluck(string $column, ?string $key = null): Collection;

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function oneByIntId(int $id);

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function oneByStringId(string $id);

    /**
     * @param string $column
     * @param int $value
     *
     * @return mixed
     */
    public function oneByInt(string $column, int $value);

    /**
     * @param string $column
     * @param string $value
     *
     * @return mixed
     */
    public function oneByString(string $column, string $value);

    /**
     * @param string $column
     *
     * @return int
     */
    public function max(string $column): int;

    /**
     * @param string $column
     *
     * @return int
     */
    public function count(string $column = '*'): int;

    /**
     * @param $column
     *
     * @return int
     */
    public function avg($column): int;

    /**
     * @param string $column
     *
     * @return int
     */
    public function sum(string $column): int;

    /**
     * @param $limit
     * @param callable $callback
     *
     * @return bool
     */
    public function chunk(int $limit, callable $callback): bool;

    /**
     * @param string $column
     * @param int $step
     * @param array $extra
     *
     * @return int
     */
    public function increment(string $column, int $step = 1, array $extra = []): int;

    /**
     * @param string $column
     * @param int $step
     * @param array $extra
     *
     * @return int
     */
    public function decrement(string $column, int $step = 1, array $extra = []): int;

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param array $data
     * @param int $id
     *
     * @return mixed
     */
    public function updateByIntId(array $data, int $id);

    /**
     * @param array $data
     * @param string $id
     *
     * @return mixed
     */
    public function updateByStringId(array $data, string $id);

    /**
     * @param int $id
     *
     * @return int
     */
    public function deleteByIntId(int $id): int;

    /**
     * @param string $id
     *
     * @return int
     */
    public function deleteByStringId(string $id): int;
}
