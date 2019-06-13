<?php

namespace CrCms\Repository\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface Repository.
 */
interface RepositoryContract
{
    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @return Collection
     */
    public function get(): Collection;

    /**
     * @param string $column
     * @param string $key
     *
     * @return Collection
     */
    public function pluck(string $column, string $key = ''): Collection;

    /**
     * @return mixed
     */
    public function first();

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function findByInt(int $id);

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function findByString(string $id);

    /**
     * @param string $field
     * @param int    $value
     *
     * @return mixed
     */
    public function oneByInt(string $field, int $value);

    /**
     * @param string $field
     * @param string $value
     *
     * @return mixed
     */
    public function oneByString(string $field, string $value);

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function byStringId(string $id);

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function byIntId(int $id);

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
     * @param string $key
     * @param string $default
     *
     * @return mixed
     */
    public function valueOfString(string $key, string $default = ''): string;

    /**
     * @param string $key
     * @param int    $default
     *
     * @return mixed
     */
    public function valueOfInt(string $key, int $default = 0): int;

    /**
     * @param string $column
     * @param int    $amount
     * @param array  $extra
     *
     * @return mixed
     */
    public function increment(string $column, int $amount = 1, array $extra = []): int;

    /**
     * @param string $column
     * @param int    $amount
     * @param array  $extra
     *
     * @return mixed
     */
    public function decrement(string $column, int $amount = 1, array $extra = []): int;

    /**
     * @param array $data
     *
     * @return int
     */
    public function update(array $data): int;

    /**
     * @param string $id
     *
     * @return int
     */
    public function deleteByStringId(string $id): int;

    /**
     * @param int $id
     *
     * @return int
     */
    public function deleteByIntId(int $id): int;

    /**
     * deleteByArray.
     *
     * @param array $ids
     *
     * @return int
     */
    public function deleteByArray(array $ids): int;

    /**
     * @return int
     */
    public function delete(): int;
}
