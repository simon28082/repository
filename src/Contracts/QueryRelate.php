<?php

namespace CrCms\Repository\Contracts;

/**
 * Interface QueryRelate.
 */
interface QueryRelate
{
    /**
     * @param array $column
     *
     * @return QueryRelate
     */
    public function select(array $column = ['*']): self;

    /**
     * @param string $expression
     * @param array  $bindings
     *
     * @return QueryRelate
     */
    public function selectRaw(string $expression, array $bindings = []): self;

    /**
     * @param int $limit
     *
     * @return QueryRelate
     */
    public function skip(int $limit): self;

    /**
     * @param int $limit
     *
     * @return QueryRelate
     */
    public function take(int $limit): self;

    /**
     * @param string $column
     *
     * @return QueryRelate
     */
    public function groupBy(string $column): self;

    /**
     * @param array $columns
     *
     * @return QueryRelate
     */
    public function groupByArray(array $columns): self;

    /**
     * @param string $column
     * @param string $sort
     *
     * @return QueryRelate
     */
    public function orderBy(string $column, string $sort = 'desc'): self;

    /**
     * @param array $columns
     *
     * @return QueryRelate
     */
    public function orderByArray(array $columns): self;

    /**
     * @param $sql
     * @param array $bindings
     *
     * @return QueryRelate
     */
    public function orderByRaw(string $sql, array $bindings = []);

    /**
     * @return QueryRelate
     */
    public function distinct(): self;

    /**
     * @param string $column
     * @param string $operator
     * @param mixed  $value
     *
     * @return QueryRelate
     */
    public function where(string $column, $operator = null, $value = null): self;

    /**
     * @param string $column
     * @param string $operator
     * @param mixed  $value
     *
     * @return QueryRelate
     */
    public function orWhere(string $column, $operator = null, $value = null): self;

    /**
     * @param \Closure $callback
     *
     * @return QueryRelate
     */
    public function whereClosure(\Closure $callback): self;

    /**
     * @param \Closure $callback
     *
     * @return QueryRelate
     */
    public function orWhereClosure(\Closure $callback): self;

    /**
     * @param string $column
     * @param array  $between
     *
     * @return QueryRelate
     */
    public function whereBetween(string $column, array $between): self;

    /**
     * @param string $column
     * @param array  $between
     *
     * @return QueryRelate
     */
    public function orWhereBetween(string $column, array $between): self;

    /**
     * @param string $sql
     * @param array  $bindings
     *
     * @return QueryRelate
     */
    public function whereRaw(string $sql, array $bindings = []): self;

    /**
     * @param string $sql
     * @param array  $bindings
     *
     * @return QueryRelate
     */
    public function orWhereRaw(string $sql, array $bindings = []): self;

    /**
     * @param $column
     * @param array $between
     *
     * @return QueryRelate
     */
    public function orWhereNotBetween($column, array $between): self;

    /**
     * @param \Closure $callback
     *
     * @return QueryRelate
     */
    public function whereExists(\Closure $callback): self;

    /**
     * @param \Closure $callback
     *
     * @return QueryRelate
     */
    public function orWhereExists(\Closure $callback): self;

    /**
     * @param \Closure $callback
     *
     * @return QueryRelate
     */
    public function whereNotExists(\Closure $callback): self;

    /**
     * @param \Closure $callback
     *
     * @return QueryRelate
     */
    public function orWhereNotExists(\Closure $callback): self;

    /**
     * @param string $column
     * @param array  $values
     *
     * @return QueryRelate
     */
    public function whereIn(string $column, array $values): self;

    /**
     * @param string $column
     * @param array  $values
     *
     * @return QueryRelate
     */
    public function orWhereIn(string $column, array $values): self;

    /**
     * @param string $column
     * @param array  $values
     *
     * @return QueryRelate
     */
    public function whereNotIn(string $column, array $values): self;

    /**
     * @param string $column
     * @param array  $values
     *
     * @return QueryRelate
     */
    public function orWhereNotIn(string $column, array $values): self;

    /**
     * @param string $column
     *
     * @return QueryRelate
     */
    public function whereNull(string $column): self;

    /**
     * @param string $column
     *
     * @return QueryRelate
     */
    public function orWhereNull(string $column): self;

    /**
     * @param string $column
     *
     * @return QueryRelate
     */
    public function whereNotNull(string $column): self;

    /**
     * @param string $column
     *
     * @return QueryRelate
     */
    public function orWhereNotNull(string $column): self;

    /**
     * @param string $sql
     *
     * @return QueryRelate
     */
    public function raw(string $sql): self;

    /**
     * @param string $table
     *
     * @return QueryRelate
     */
    public function from(string $table): self;

    /**
     * @param string $table
     * @param string $one
     * @param string $operator
     * @param string $two
     *
     * @return QueryRelate
     */
    public function join(string $table, string $one, string $operator = '=', string $two = ''): self;

    /**
     * @param string   $table
     * @param \Closure $callback
     *
     * @return QueryRelate
     */
    public function joinClosure(string $table, \Closure $callback): self;

    /**
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $two
     *
     * @return QueryRelate
     */
    public function leftJoin(string $table, string $first, string $operator = '=', string $two = ''): self;

    /**
     * @param string   $table
     * @param \Closure $callback
     *
     * @return QueryRelate
     */
    public function leftJoinClosure(string $table, \Closure $callback): self;

    /**
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $two
     *
     * @return QueryRelate
     */
    public function rightJoin(string $table, string $first, string $operator = '=', string $two = ''): self;

    /**
     * @param string   $table
     * @param \Closure $callback
     *
     * @return QueryRelate
     */
    public function rightJoinClosure(string $table, \Closure $callback): self;

    /**
     * @param callable $callable
     *
     * @return QueryRelate
     */
    public function callable(callable $callable): self;

    /**
     * @param array $array
     *
     * @return QueryRelate
     */
    public function whereArray(array $array): self;

    /**
     * @param QueryRelate $queryRelate
     * @param bool        $unionAll
     *
     * @return QueryRelate
     */
    public function union(self $queryRelate, bool $unionAll = true): self;

    /**
     * @param QueryMagic $queryMagic
     *
     * @return QueryRelate
     */
    public function magic(QueryMagic $queryMagic): self;

    /**
     * @param QueryMagic|null $queryMagic
     *
     * @return QueryRelate
     */
    public function whenMagic(?QueryMagic $queryMagic = null): self;

    /**
     * @param bool     $condition
     * @param callable $trueCallable
     * @param callable $falseCallable
     *
     * @return QueryRelate
     */
    public function when(bool $condition, callable $trueCallable, callable $falseCallable): self;

    /**
     * @param array $conditions
     * @param array $callables
     *
     * @return QueryRelate
     */
    public function whenMultiple(array $conditions, array $callables): self;

    /**
     * @param array $relations
     *
     * @return QueryRelate
     */
    public function withArray(array $relations): self;

    /**
     * @param string $relation
     *
     * @return QueryRelate
     */
    public function with(string $relation): self;

    /**
     * @param array $relations
     *
     * @return QueryRelate
     */
    public function withoutArray(array $relations): self;

    /**
     * @param string $relation
     *
     * @return QueryRelate
     */
    public function without(string $relation): self;
}
