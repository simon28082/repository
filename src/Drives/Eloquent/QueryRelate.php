<?php
namespace CrCms\Repository\Eloquent;

use CrCms\Repository\Contracts\Eloquent\QueryMagic;
use CrCms\Repository\Contracts\QueryRelate as BaseQueryRelate;
use CrCms\Repository\Contracts\Eloquent\QueryRelate as BaseEloquentQueryRelate;
use CrCms\Repository\Contracts\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class QueryRelate
 * @package CrCms\Repository\Eloquent
 */
class QueryRelate implements BaseQueryRelate,BaseEloquentQueryRelate
{

    /**
     * @var null
     */
    protected $query = null;

    /**
     * @var null
     */
    protected $repository = null;

    /**
     * QueryRelate constructor.
     * @param Builder $query
     * @param Repository $repository
     */
    public function __construct(Builder $query, Repository $repository)
    {
        $this->setQuery($query);
        $this->setRepository($repository);
    }


    /**
     * @param Repository $repository
     * @return BaseQueryRelate
     */
    public function setRepository(Repository $repository) : BaseQueryRelate
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * @return Repository
     */
    public function getRepository() : Repository
    {
        return $this->repository;
    }


    /**
     * @return Builder
     */
    public function getQuery() : Builder
    {
        return $this->query;
    }

    /**
     * @param Builder $query
     * @return BaseQueryRelate
     */
    public function setQuery(Builder $query) : BaseQueryRelate
    {
        $this->query = $query;
        return $this;
    }


    /**
     * @param array $column
     * @return BaseQueryRelate
     */
    public function select(array $column = ['*']): BaseQueryRelate
    {
        $this->query->select($column);
        return $this;
    }

    /**
     * @param string $expression
     * @param array $bindings
     * @return BaseQueryRelate
     */
    public function selectRaw(string $expression, array $bindings = []): BaseQueryRelate
    {
        $this->query->selectRaw($expression,$bindings);
        return $this;
    }

    /**
     * @param int $limit
     * @return BaseQueryRelate
     */
    public function skip(int $limit): BaseQueryRelate
    {
        $this->query->skip($limit);
        return $this;
    }

    /**
     * @param int $limit
     * @return BaseQueryRelate
     */
    public function take(int $limit): BaseQueryRelate
    {
        $this->query->take($limit);
        return $this;
    }

    /**
     * @param string $column
     * @return BaseQueryRelate
     */
    public function groupBy(string $column): BaseQueryRelate
    {
        $this->query->groupBy($column);
        return $this;
    }

    /**
     * @param array $columns
     * @return BaseQueryRelate
     */
    public function groupByArray(array $columns): BaseQueryRelate
    {
        $this->query->groupBy($columns);
        return $this;
    }

    /**
     * @param string $column
     * @param string $sort
     * @return BaseQueryRelate
     */
    public function orderBy(string $column, string $sort = 'desc'): BaseQueryRelate
    {
        $this->query->orderBy($column,$sort);
        return $this;
    }

    /**
     * @param array $columns
     * @return BaseQueryRelate
     */
    public function orderByArray(array $columns): BaseQueryRelate
    {
        array_map(function($value,$key){
           $this->query->orderBy($key,$value);
        },$columns);
        return $this;
    }

    /**
     * @return BaseQueryRelate
     */
    public function distinct(): BaseQueryRelate
    {
        // TODO: Implement distinct() method.
        $this->query->distinct();
        return $this;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return BaseQueryRelate
     */
    public function where(string $column, string $operator = '=', string $value = ''): BaseQueryRelate
    {
        $this->query->where($column,$operator,$value);
        return $this;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return BaseQueryRelate
     */
    public function orWhere(string $column, string $operator = '=', string $value = ''): BaseQueryRelate
    {
        $this->query->orWhere($column,$operator,$value);
        return $this;
    }

    /**
     * @param string $column
     * @param array $between
     * @return BaseQueryRelate
     */
    public function whereBetween(string $column, array $between): BaseQueryRelate
    {
        $this->query->whereBetween($column,$between);
        return $this;
    }

    /**
     * @param string $column
     * @param array $between
     * @return BaseQueryRelate
     */
    public function orWhereBetween(string $column, array $between): BaseQueryRelate
    {
        $this->query->orWhereBetween($column,$between);
        return $this;
    }

    /**
     * @param string $sql
     * @param array $bindings
     * @return BaseQueryRelate
     */
    public function whereRaw(string $sql, array $bindings = []): BaseQueryRelate
    {
        $this->query->whereRaw($sql,$bindings);
        return $this;
    }

    /**
     * @param string $sql
     * @param array $bindings
     * @return BaseQueryRelate
     */
    public function orWhereRaw(string $sql, array $bindings = []): BaseQueryRelate
    {
        $this->query->orWhereRaw($sql,$bindings);
        return $this;
    }

    /**
     * @param $column
     * @param array $between
     * @return BaseQueryRelate
     */
    public function orWhereNotBetween($column, array $between): BaseQueryRelate
    {
        $this->query->orWhereNotBetween($column,$between);
        return $this;
    }

    /**
     * @param \Closure $callback
     * @return BaseQueryRelate
     */
    public function whereExists(\Closure $callback): BaseQueryRelate
    {
        $this->query->whereExists($callback);
        return $this;
    }

    /**
     * @param \Closure $callback
     * @return BaseQueryRelate
     */
    public function orWhereExists(\Closure $callback): BaseQueryRelate
    {
        $this->query->orWhereExists($callback);
        return $this;
    }

    /**
     * @param \Closure $callback
     * @return BaseQueryRelate
     */
    public function whereNotExists(\Closure $callback): BaseQueryRelate
    {
        $this->query->whereNotExists($callback);
        return $this;
    }

    /**
     * @param \Closure $callback
     * @return BaseQueryRelate
     */
    public function orWhereNotExists(\Closure $callback): BaseQueryRelate
    {
        $this->query->orWhereNotExists($callback);
        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return BaseQueryRelate
     */
    public function whereIn(string $column, array $values): BaseQueryRelate
    {
        $this->query->whereIn($column,$values);
        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return BaseQueryRelate
     */
    public function orWhereIn(string $column, array $values): BaseQueryRelate
    {
        $this->query->orWhereIn($column,$values);
        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return BaseQueryRelate
     */
    public function whereNotIn(string $column, array $values): BaseQueryRelate
    {
        $this->query->whereNotIn($column,$values);
        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return BaseQueryRelate
     */
    public function orWhereNotIn(string $column, array $values): BaseQueryRelate
    {
        $this->query->orWhereNotIn($column,$values);
        return $this;
    }

    /**
     * @param string $column
     * @return BaseQueryRelate
     */
    public function whereNull(string $column): BaseQueryRelate
    {
        $this->query->whereNull($column);
        return $this;
    }

    /**
     * @param string $column
     * @return BaseQueryRelate
     */
    public function orWhereNull(string $column): BaseQueryRelate
    {
        $this->query->orWhereNull($column);
        return $this;
    }

    /**
     * @param string $column
     * @return BaseQueryRelate
     */
    public function whereNotNull(string $column): BaseQueryRelate
    {
        $this->query->whereNotNull($column);
        return $this;
    }

    /**
     * @param string $column
     * @return BaseQueryRelate
     */
    public function orWhereNotNull(string $column): BaseQueryRelate
    {
        $this->query->orWhereNotNull($column);
        return $this;
    }


    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return BaseEloquentQueryRelate
     */
    public function union(\Illuminate\Database\Query\Builder $query): BaseEloquentQueryRelate
    {
        $this->query->union($query);
        return $this;
    }


    /**
     * @param string $sql
     * @return BaseQueryRelate
     */
    public function raw(string $sql): BaseQueryRelate
    {
        $this->query->raw($sql);
        return $this;
    }

    /**
     * @param string $table
     * @return BaseQueryRelate
     */
    public function from(string $table): BaseQueryRelate
    {
        $this->query->raw($table);
        return $this;
    }

    /**
     * @param string $table
     * @param string $one
     * @param string $operator
     * @param string $two
     * @return BaseQueryRelate
     */
    public function join(string $table, string $one, string $operator = '=', string $two = ''): BaseQueryRelate
    {
        $this->query->join($table,$one,$operator,$two);
        return $this;
    }

    /**
     * @param string $table
     * @param \Closure $callback
     * @return BaseQueryRelate
     */
    public function joinByClosure(string $table, \Closure $callback): BaseQueryRelate
    {
        $this->query->join($table,$callback);
        return $this;
    }

    /**
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $two
     * @return BaseQueryRelate
     */
    public function leftJoin(string $table, string $first, string $operator = '=', string $two = ''): BaseQueryRelate
    {
        $this->query->leftjoin($table,$first,$operator,$two);
        return $this;
    }

    /**
     * @param string $table
     * @param \Closure $callback
     * @return BaseQueryRelate
     */
    public function leftJoinByClosure(string $table, \Closure $callback): BaseQueryRelate
    {
        $this->query->leftjoin($table,$callback);
        return $this;
    }

    /**
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $two
     * @return BaseQueryRelate
     */
    public function rightJoin(string $table, string $first, string $operator = '=', string $two = ''): BaseQueryRelate
    {
        $this->query->rightJoin($table,$first,$operator,$two);
        return $this;
    }

    /**
     * @param string $table
     * @param \Closure $callback
     * @return BaseQueryRelate
     */
    public function rightJoinByClosure(string $table, \Closure $callback): BaseQueryRelate
    {
        $this->query->rightJoinByClosure($table,$callback);
        return $this;
    }

    /**
     * @param callable $callable
     * @return BaseQueryRelate
     */
    public function callable(callable $callable): BaseQueryRelate
    {
        $this->query = call_user_func($callable,$this->query);
        return $this;
    }

    /**
     * @param array $wheres
     * @return BaseQueryRelate
     */
    public function wheres(array $wheres): BaseQueryRelate
    {
        $this->query = (new ResolveWhereQuery)->getQuery($wheres,$this->query);
        return $this;
    }

    /**
     * @param QueryMagic $queryMagic
     * @return BaseEloquentQueryRelate
     */
    public function magic(QueryMagic $queryMagic): BaseEloquentQueryRelate
    {
        $this->query = $queryMagic->magic($this->query,$this->repository);
        return $this;
    }


    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->repository,$name)) {
            return call_user_func_array([$this->repository,$name],$arguments);
        }

        $className = static::class;
        throw new \Exception("Call to undefined method {$className}::{$name}");
    }

}