<?php

namespace CrCms\Repository\Contracts;

/**
 * Interface QueryRelate
 *
 * @package CrCms\Repository\Contracts
 */
interface QueryRelate
{
    /**
     * @param array $column
     * @return QueryRelate
     */
    public function select(array $column = ['*']): QueryRelate;

    /**
     * @param string $expression
     * @param array $bindings
     * @return QueryRelate
     */
    public function selectRaw(string $expression, array $bindings = []): QueryRelate;

    /**
     * @param int $limit
     * @return QueryRelate
     */
    public function skip(int $limit): QueryRelate;

    /**
     * @param int $limit
     * @return QueryRelate
     */
    public function take(int $limit): QueryRelate;

    /**
     * @param string $column
     * @return QueryRelate
     */
    public function groupBy(string $column): QueryRelate;

    /**
     * @param array $columns
     * @return QueryRelate
     */
    public function groupByArray(array $columns): QueryRelate;

    /**
     * @param string $column
     * @param string $sort
     * @return QueryRelate
     */
    public function orderBy(string $column, string $sort = 'desc'): QueryRelate;

    /**
     * @param array $columns
     * @return QueryRelate
     */
    public function orderByArray(array $columns): QueryRelate;

    /**
     * @return QueryRelate
     */
    public function distinct(): QueryRelate;

    /**
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return QueryRelate
     */
    public function where(string $column, string $operator = '=', string $value = ''): QueryRelate;

    /**
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return QueryRelate
     */
    public function orWhere(string $column, string $operator = '=', string $value = ''): QueryRelate;

    /**
     * @param string $column
     * @param array $between
     * @return QueryRelate
     */
    public function whereBetween(string $column, array $between): QueryRelate;

    /**
     * @param string $column
     * @param array $between
     * @return QueryRelate
     */
    public function orWhereBetween(string $column, array $between): QueryRelate;

    /**
     * @param string $sql
     * @param array $bindings
     * @return QueryRelate
     */
    public function whereRaw(string $sql, array $bindings = []): QueryRelate;

    /**
     * @param string $sql
     * @param array $bindings
     * @return QueryRelate
     */
    public function orWhereRaw(string $sql, array $bindings = []): QueryRelate;

    /**
     * @param $column
     * @param array $between
     * @return QueryRelate
     */
    public function orWhereNotBetween($column, array $between): QueryRelate;

    /**
     * @param \Closure $callback
     * @return QueryRelate
     */
    public function whereExists(\Closure $callback): QueryRelate;

    /**
     * @param \Closure $callback
     * @return QueryRelate
     */
    public function orWhereExists(\Closure $callback): QueryRelate;

    /**
     * @param \Closure $callback
     * @return QueryRelate
     */
    public function whereNotExists(\Closure $callback): QueryRelate;

    /**
     * @param \Closure $callback
     * @return QueryRelate
     */
    public function orWhereNotExists(\Closure $callback): QueryRelate;

    /**
     * @param string $column
     * @param array $values
     * @return QueryRelate
     */
    public function whereIn(string $column, array $values): QueryRelate;

    /**
     * @param string $column
     * @param array $values
     * @return QueryRelate
     */
    public function orWhereIn(string $column, array $values): QueryRelate;

    /**
     * @param string $column
     * @param array $values
     * @return QueryRelate
     */
    public function whereNotIn(string $column, array $values): QueryRelate;

    /**
     * @param string $column
     * @param array $values
     * @return QueryRelate
     */
    public function orWhereNotIn(string $column, array $values): QueryRelate;

    /**
     * @param string $column
     * @return QueryRelate
     */
    public function whereNull(string $column): QueryRelate;

    /**
     * @param string $column
     * @return QueryRelate
     */
    public function orWhereNull(string $column): QueryRelate;

    /**
     * @param string $column
     * @return QueryRelate
     */
    public function whereNotNull(string $column): QueryRelate;

    /**
     * @param string $column
     * @return QueryRelate
     */
    public function orWhereNotNull(string $column): QueryRelate;

    /**
     * @param string $sql
     * @return QueryRelate
     */
    public function raw(string $sql): QueryRelate;

    /**
     * @param string $table
     * @return QueryRelate
     */
    public function from(string $table): QueryRelate;

    /**
     * @param string $table
     * @param string $one
     * @param string $operator
     * @param string $two
     * @return QueryRelate
     */
    public function join(string $table, string $one, string $operator = '=', string $two = ''): QueryRelate;

    /**
     * @param string $table
     * @param \Closure $callback
     * @return QueryRelate
     */
    public function joinByClosure(string $table, \Closure $callback): QueryRelate;

    /**
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $two
     * @return QueryRelate
     */
    public function leftJoin(string $table, string $first, string $operator = '=', string $two = ''): QueryRelate;

    /**
     * @param string $table
     * @param \Closure $callback
     * @return QueryRelate
     */
    public function leftJoinByClosure(string $table, \Closure $callback): QueryRelate;

    /**
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $two
     * @return QueryRelate
     */
    public function rightJoin(string $table, string $first, string $operator = '=', string $two = ''): QueryRelate;

    /**
     * @param string $table
     * @param \Closure $callback
     * @return QueryRelate
     */
    public function rightJoinByClosure(string $table, \Closure $callback): QueryRelate;

    /**
     * @param callable $callable
     * @return QueryRelate
     */
    public function callable(callable $callable): QueryRelate;

    /**
     * @param array $array
     * @return QueryRelate
     */
    public function whereArray(array $array): QueryRelate;

    /**
     * @param QueryRelate $queryRelate
     * @return QueryRelate
     */
    public function union(QueryRelate $queryRelate): QueryRelate;

    /**
     * @param QueryMagic $queryMagic
     * @return QueryRelate
     */
    public function magic(QueryMagic $queryMagic): QueryRelate;
}