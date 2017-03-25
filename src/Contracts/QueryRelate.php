<?php
namespace CrCms\Repository\Contracts;

interface QueryRelate
{

    public function select(array $column = ['*']) : QueryRelate;


    public function selectRaw(string $expression, array $bindings = []) : QueryRelate;


    public function skip(int $limit) : QueryRelate ;


    public function take(int $limit) : QueryRelate;

    public function groupBy(string $column) : QueryRelate ;


    public function groupByArray(array $columns) : QueryRelate;


    public function orderBy(string $column, string $sort = 'desc') : QueryRelate ;

    public function orderByArray(array $columns) : QueryRelate;

    public function distinct() : QueryRelate;

    public function where(string $column, string $operator = '=', string $value = '') : QueryRelate;

    public function orWhere(string $column, string $operator = '=', string $value = '') : QueryRelate;


    public function whereBetween(string $column, array $between) : QueryRelate;

    public function orWhereBetween(string $column, array $between) : QueryRelate;

    public function whereRaw(string $sql, array $bindings = []) : QueryRelate;


    public function orWhereRaw(string $sql, array $bindings = []) : QueryRelate;


    public function orWhereNotBetween($column, array $between) : QueryRelate;


    public function whereExists(\Closure $callback) : QueryRelate;


    public function orWhereExists(\Closure $callback) : QueryRelate;


    public function whereNotExists(\Closure $callback) : QueryRelate;


    public function orWhereNotExists(\Closure $callback) : QueryRelate;


    public function whereIn(string $column, array $values) : QueryRelate;


    public function orWhereIn(string $column, array $values) : QueryRelate;


    public function whereNotIn(string $column, array $values) : QueryRelate;

    public function orWhereNotIn(string $column, array $values) : QueryRelate;


    public function whereNull(string $column) : QueryRelate;


    public function orWhereNull(string $column) : QueryRelate;


    public function whereNotNull(string $column) : QueryRelate;


    public function orWhereNotNull(string $column) : QueryRelate;


    public function raw(string $sql) : QueryRelate;

    public function from(string $table) : QueryRelate;


    public function join(string $table, string $one, string $operator = '=', string $two = '') : QueryRelate;


    public function joinByClosure(string $table, \Closure $callback) : QueryRelate;

    public function leftJoin(string $table, string $first, string $operator = '=', string $two = '') : QueryRelate;


    public function leftJoinByClosure(string $table, \Closure $callback) : QueryRelate;


    public function rightJoin(string $table, string $first, string $operator = '=', string $two = '') : QueryRelate;


    public function rightJoinByClosure(string $table, \Closure $callback) : QueryRelate;


    public function callable(callable $callable) : QueryRelate;


    public function wheres(array $wheres) : QueryRelate;


    public function union(QueryRelate $queryRelate) : QueryRelate;

    public function magic(QueryMagic $queryMagic) : QueryRelate;

}