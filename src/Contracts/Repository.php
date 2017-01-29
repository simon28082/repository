<?php
namespace CrCms\Repository\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface Repository
 * @package CrCms\Repository\Contracts
 */
interface Repository
{
    /**
     * @param array $columns
     * @return Collection
     */
    public function all() : Collection;


    /**
     * @return Collection
     */
    public function get() : Collection;


    /**
     * @param string $column
     * @param string $key
     * @return Collection
     */
    public function pluck(string $column, string $key = '') : Collection;


    /**
     * @param string $column
     * @return int
     */
    public function max(string $column) : int;


    /**
     * @return int
     */
    public function count(string $column = '*') : int;


    /**
     * @param $column
     * @return int
     */
    public function avg($column) : int;


    /**
     * @param string $column
     * @return int
     */
    public function sum(string $column) : int;


    /**
     * @param $limit
     * @param callable $callback
     * @return void
     */
    public function chunk(int $limit, callable $callback) ;


    /**
     * @param string $key
     * @return mixed
     */
    public function value(string $key);


    /**
     * @param string $column
     * @param int $amount
     * @param array $extra
     * @return mixed
     */
    public function increment(string $column, int $amount = 1, array $extra = []);


    /**
     * @param string $column
     * @param int $amount
     * @param array $extra
     * @return mixed
     */
    public function decrement(string $column, int $amount = 1, array $extra = []);
}