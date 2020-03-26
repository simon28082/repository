<?php
declare(strict_types = 1);

namespace CrCms\Repository\Contracts;

use Illuminate\Support\Collection;

interface Database extends Any
{
    /**
     * @param array $columns
     *
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * @param string $column
     * @param string|null $key
     *
     * @return Collection
     */
    public function pluck(string $column, ?string $key = null): Collection;

    /**
     * @param string|int $key
     *
     * @return object|null
     */
    public function oneByKey($key);

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
     * @return object
     */
    public function create(array $data);

    /**
     * @param array $data
     * @param string|int $id
     *
     * @return object
     */
    public function update(array $data, $key);

    /**
     * @param string|int $key
     *
     * @return int
     */
    public function delete(string $key): int;
}
