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
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return Collection
     */
    public function by(string $field, string $value) : Collection;


    /**
     * @param string $column
     * @return int
     */
    public function max(string $column) : int;


    /**
     * @return int
     */
    public function count(string $column) : int;


    /**
     * @param string $column
     * @return int
     */
    public function sum(string $column) : int;

}