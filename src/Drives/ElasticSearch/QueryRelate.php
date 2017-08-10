<?php

namespace CrCms\Repository\Drives\ElasticSearch;

use CrCms\Repository\AbstractRepository;
use CrCms\Repository\Contracts\QueryMagic;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Drives\QueryRelate as BaseQueryRelate;
use CrCms\Repository\Contracts\QueryRelate as BaseQueryRelateContract;

/**
 * Class QueryRelate
 *
 * @package CrCms\Repository\Drives\ElasticSearch
 */
class QueryRelate extends BaseQueryRelate
{

    public function __construct(Builder $builder,Repository $repository)
    {
        $this->setQuery($builder);
        $this->setRepository($repository);
    }


    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }

    /**
     * @param Builder $query
     * @return BaseQueryRelate
     */
    public function setQuery(Builder $query): QueryRelate
    {
        $this->query = $query;
        return $this;
    }


    public function select(array $column = ['*']): BaseQueryRelateContract
    {
        $this->query->select($column);

        return $this;
    }

    public function selectRaw(string $expression, array $bindings = []): BaseQueryRelateContract
    {
        // TODO: Implement selectRaw() method.
    }

    public function skip(int $limit): BaseQueryRelateContract
    {
        // TODO: Implement skip() method.
    }

    public function take(int $limit): BaseQueryRelateContract
    {
        // TODO: Implement take() method.
    }

    public function groupBy(string $column): BaseQueryRelateContract
    {
        // TODO: Implement groupBy() method.
    }

    public function groupByArray(array $columns): BaseQueryRelateContract
    {
        // TODO: Implement groupByArray() method.
    }

    public function orderBy(string $column, string $sort = 'desc'): BaseQueryRelateContract
    {
        // TODO: Implement orderBy() method.
    }

    public function orderByArray(array $columns): BaseQueryRelateContract
    {
        // TODO: Implement orderByArray() method.
    }

    public function distinct(): BaseQueryRelateContract
    {
        // TODO: Implement distinct() method.
    }

    public function where(string $column, string $operator = '=', string $value = ''): BaseQueryRelateContract
    {
        // TODO: Implement where() method.
    }

    public function orWhere(string $column, string $operator = '=', string $value = ''): BaseQueryRelateContract
    {
        // TODO: Implement orWhere() method.
    }

    public function whereClosure(\Closure $callback): BaseQueryRelateContract
    {
        // TODO: Implement whereClosure() method.
    }

    public function orWhereClosure(\Closure $callback): BaseQueryRelateContract
    {
        // TODO: Implement orWhereClosure() method.
    }

    public function whereBetween(string $column, array $between): BaseQueryRelateContract
    {
        // TODO: Implement whereBetween() method.
    }

    public function orWhereBetween(string $column, array $between): BaseQueryRelateContract
    {
        // TODO: Implement orWhereBetween() method.
    }

    public function whereRaw(string $sql, array $bindings = []): BaseQueryRelateContract
    {
        // TODO: Implement whereRaw() method.
    }

    public function orWhereRaw(string $sql, array $bindings = []): BaseQueryRelateContract
    {
        // TODO: Implement orWhereRaw() method.
    }

    public function orWhereNotBetween($column, array $between): BaseQueryRelateContract
    {
        // TODO: Implement orWhereNotBetween() method.
    }

    public function whereExists(\Closure $callback): BaseQueryRelateContract
    {
        // TODO: Implement whereExists() method.
    }

    public function orWhereExists(\Closure $callback): BaseQueryRelateContract
    {
        // TODO: Implement orWhereExists() method.
    }

    public function whereNotExists(\Closure $callback): BaseQueryRelateContract
    {
        // TODO: Implement whereNotExists() method.
    }

    public function orWhereNotExists(\Closure $callback): BaseQueryRelateContract
    {
        // TODO: Implement orWhereNotExists() method.
    }

    public function whereIn(string $column, array $values): BaseQueryRelateContract
    {
        // TODO: Implement whereIn() method.
    }

    public function orWhereIn(string $column, array $values): BaseQueryRelateContract
    {
        // TODO: Implement orWhereIn() method.
    }

    public function whereNotIn(string $column, array $values): BaseQueryRelateContract
    {
        // TODO: Implement whereNotIn() method.
    }

    public function orWhereNotIn(string $column, array $values): BaseQueryRelateContract
    {
        // TODO: Implement orWhereNotIn() method.
    }

    public function whereNull(string $column): BaseQueryRelateContract
    {
        // TODO: Implement whereNull() method.
    }

    public function orWhereNull(string $column): BaseQueryRelateContract
    {
        // TODO: Implement orWhereNull() method.
    }

    public function whereNotNull(string $column): BaseQueryRelateContract
    {
        // TODO: Implement whereNotNull() method.
    }

    public function orWhereNotNull(string $column): BaseQueryRelateContract
    {
        // TODO: Implement orWhereNotNull() method.
    }

    public function raw(string $sql): BaseQueryRelateContract
    {
        // TODO: Implement raw() method.
    }

    public function from(string $table): BaseQueryRelateContract
    {
        // TODO: Implement from() method.
    }

    public function join(string $table, string $one, string $operator = '=', string $two = ''): BaseQueryRelateContract
    {
        // TODO: Implement join() method.
    }

    public function joinClosure(string $table, \Closure $callback): BaseQueryRelateContract
    {
        // TODO: Implement joinClosure() method.
    }

    public function leftJoin(string $table, string $first, string $operator = '=', string $two = ''): BaseQueryRelateContract
    {
        // TODO: Implement leftJoin() method.
    }

    public function leftJoinClosure(string $table, \Closure $callback): BaseQueryRelateContract
    {
        // TODO: Implement leftJoinClosure() method.
    }

    public function rightJoin(string $table, string $first, string $operator = '=', string $two = ''): BaseQueryRelateContract
    {
        // TODO: Implement rightJoin() method.
    }

    public function rightJoinClosure(string $table, \Closure $callback): BaseQueryRelateContract
    {
        // TODO: Implement rightJoinClosure() method.
    }

    public function callable(callable $callable): BaseQueryRelateContract
    {
        // TODO: Implement callable() method.
    }

    public function whereArray(array $array): BaseQueryRelateContract
    {
        // TODO: Implement whereArray() method.
    }

    public function union(BaseQueryRelateContract $queryRelate): BaseQueryRelateContract
    {
        // TODO: Implement union() method.
    }

    public function magic(QueryMagic $queryMagic): BaseQueryRelateContract
    {
        // TODO: Implement magic() method.
    }


}