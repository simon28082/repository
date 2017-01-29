<?php
namespace CrCms\Repository\Eloquent;

use CrCms\Repository\Contracts\Eloquent\Eloquent;
use CrCms\Repository\Contracts\Eloquent\QueryMagic;
use CrCms\Repository\Contracts\QueryRelate as BaseQueryRelate;
use CrCms\Repository\Contracts\Eloquent\QueryRelate as BaseEloquentQueryRelate;
use CrCms\Repository\Contracts\Repository;
use Illuminate\Database\Eloquent\Builder;

class QueryRelate implements BaseQueryRelate,BaseEloquentQueryRelate
{

    protected $query = null;

    protected $repository = null;

    public function __construct(Builder $query,Repository $repository)
    {
        $this->query = $query;
        $this->repository = $repository;
    }


    public function getQuery() : Builder
    {
        return $this->query;
    }


    public function select(array $column = ['*']): BaseQueryRelate
    {
        $this->query->select($column);
        return $this;
    }

    public function selectRaw(string $expression, array $bindings = []): BaseQueryRelate
    {
        $this->query->selectRaw($expression,$bindings);
        return $this;
    }

    public function skip(int $limit): BaseQueryRelate
    {
        $this->query->skip($limit);
        return $this;
    }

    public function take(int $limit): BaseQueryRelate
    {
        $this->query->take($limit);
        return $this;
    }

    public function groupBy(string $column): BaseQueryRelate
    {
        $this->query->groupBy($column);
        return $this;
    }

    public function groupByArray(array $columns): BaseQueryRelate
    {
        $this->query->groupBy($columns);
        return $this;
    }

    public function orderBy(string $column, string $sort = 'desc'): BaseQueryRelate
    {
        $this->query->orderBy($column,$sort);
        return $this;
    }

    public function orderByArray(array $columns): BaseQueryRelate
    {
        array_map(function($value,$key){
           $this->query->orderBy($key,$value);
        },$columns);
        return $this;
    }

    public function distinct(): BaseQueryRelate
    {
        // TODO: Implement distinct() method.
        $this->query->distinct();
        return $this;
    }

    public function where(string $column, string $operator = '=', string $value = ''): BaseQueryRelate
    {
        $this->query->where($column,$operator,$value);
        return $this;
    }

    public function orWhere(string $column, string $operator = '=', string $value = ''): BaseQueryRelate
    {
        $this->query->orWhere($column,$operator,$value);
        return $this;
    }

    public function whereBetween(string $column, array $between): BaseQueryRelate
    {
        $this->query->whereBetween($column,$between);
        return $this;
    }

    public function orWhereBetween(string $column, array $between): BaseQueryRelate
    {
        $this->query->orWhereBetween($column,$between);
        return $this;
    }

    public function whereRaw(string $sql, array $bindings = []): BaseQueryRelate
    {
        $this->query->whereRaw($sql,$bindings);
        return $this;
    }

    public function orWhereRaw(string $sql, array $bindings = []): BaseQueryRelate
    {
        $this->query->orWhereRaw($sql,$bindings);
        return $this;
    }

    public function orWhereNotBetween($column, array $between): BaseQueryRelate
    {
        $this->query->orWhereNotBetween($column,$between);
        return $this;
    }

    public function whereExists(\Closure $callback): BaseQueryRelate
    {
        $this->query->whereExists($callback);
        return $this;
    }

    public function orWhereExists(\Closure $callback): BaseQueryRelate
    {
        $this->query->orWhereExists($callback);
        return $this;
    }

    public function whereNotExists(\Closure $callback): BaseQueryRelate
    {
        $this->query->whereNotExists($callback);
        return $this;
    }

    public function orWhereNotExists(\Closure $callback): BaseQueryRelate
    {
        $this->query->orWhereNotExists($callback);
        return $this;
    }

    public function whereIn(string $column, array $values): BaseQueryRelate
    {
        $this->query->whereIn($column,$values);
        return $this;
    }

    public function orWhereIn(string $column, array $values): BaseQueryRelate
    {
        $this->query->orWhereIn($column,$values);
        return $this;
    }

    public function whereNotIn(string $column, array $values): BaseQueryRelate
    {
        $this->query->whereNotIn($column,$values);
        return $this;
    }

    public function orWhereNotIn(string $column, array $values): BaseQueryRelate
    {
        $this->query->orWhereNotIn($column,$values);
        return $this;
    }

    public function whereNull(string $column): BaseQueryRelate
    {
        $this->query->whereNull($column);
        return $this;
    }

    public function orWhereNull(string $column): BaseQueryRelate
    {
        $this->query->orWhereNull($column);
        return $this;
    }

    public function whereNotNull(string $column): BaseQueryRelate
    {
        $this->query->whereNotNull($column);
        return $this;
    }

    public function orWhereNotNull(string $column): BaseQueryRelate
    {
        $this->query->orWhereNotNull($column);
        return $this;
    }


    public function union(\Illuminate\Database\Query\Builder $query): BaseEloquentQueryRelate
    {
        $this->query->union($query);
        return $this;
    }


    public function raw(string $sql): BaseQueryRelate
    {
        $this->query->raw($sql);
        return $this;
    }

    public function from(string $table): BaseQueryRelate
    {
        $this->query->raw($table);
        return $this;
    }

    public function join(string $table, string $one, string $operator = '=', string $two = ''): BaseQueryRelate
    {
        $this->query->join($table,$one,$operator,$two);
        return $this;
    }

    public function joinByClosure(string $table, \Closure $callback): BaseQueryRelate
    {
        $this->query->join($table,$callback);
        return $this;
    }

    public function leftJoin(string $table, string $first, string $operator = '=', string $two = ''): BaseQueryRelate
    {
        $this->query->leftjoin($table,$first,$operator,$two);
        return $this;
    }

    public function leftJoinByClosure(string $table, \Closure $callback): BaseQueryRelate
    {
        $this->query->leftjoin($table,$callback);
        return $this;
    }

    public function rightJoin(string $table, string $first, string $operator = '=', string $two = ''): BaseQueryRelate
    {
        $this->query->rightJoin($table,$first,$operator,$two);
        return $this;
    }

    public function rightJoinByClosure(string $table, \Closure $callback): BaseQueryRelate
    {
        $this->query->rightJoinByClosure($table,$callback);
        return $this;
    }

    public function callable(callable $callable): BaseQueryRelate
    {
        $this->query = call_user_func($callable,$this->query);
        return $this;
    }

    public function wheres(array $wheres): BaseQueryRelate
    {
        $this->query = (new ResolveWhereQuery)->getQuery($wheres,$this->query);
        return $this;
    }

    public function magic(QueryMagic $queryMagic): BaseEloquentQueryRelate
    {
        $this->query = $queryMagic->magic($this->query,$this->repository);
        return $this;
    }


}