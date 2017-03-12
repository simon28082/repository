<?php
namespace CrCms\Repository\Contracts;

/**
 * Interface RepositoryQuery
 * @package CrCms\Repository\Contracts
 */
interface RepositoryQuery
{

    /**
     * @param array $column
     * @return RepositoryQuery
     */
    public function column(array $column = ['*']) : RepositoryQuery;


    /**
     * @param int $limit
     * @return RepositoryQuery
     */
    public function skip(int $limit) : RepositoryQuery ;


    /**
     * @param int $limit
     * @return RepositoryQuery
     */
    public function take(int $limit) : RepositoryQuery;


    /**
     * @param string $column
     * @return RepositoryQuery
     */
    public function groupBy(string $column) : RepositoryQuery ;


    /**
     * @param string $column
     * @param string $sort
     * @return RepositoryQuery
     */
    public function orderBy(string $column, string $sort = 'desc') : RepositoryQuery ;


    /**
     * @param callable $callable
     * @return RepositoryQuery
     */
    public function callable(callable $callable) : RepositoryQuery;


    /**
     * @param array $wheres
     * @return RepositoryQuery
     */
    public function wheres(array $wheres) : RepositoryQuery;

}