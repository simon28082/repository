<?php
namespace CrCms\Repository\Eloquent;

use CrCms\Repository\Drives\Eloquent\ResolveWhereQuery;
use CrCms\Repository\Drives\QueryRelate as BaseQueryRelate;
use CrCms\Repository\Contracts\QueryRelate as BaseQueryRelateContract;
use CrCms\Repository\Contracts\QueryMagic;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Exceptions\MethodNotFoundException;
use Illuminate\Database\Eloquent\Builder;

class QueryRelate extends BaseQueryRelate implements BaseQueryRelateContract
{

    public function __construct(Builder $query, Repository $repository)
    {
        $this->setQuery($query);
        $this->setRepository($repository);
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
    public function select(array $column = ['*']): BaseQueryRelateContract
    {
        $this->query->select($column);
        return $this;
    }

    public function selectRaw(string $expression, array $bindings = []): BaseQueryRelateContract
    {
        $this->query->selectRaw($expression,$bindings);
        return $this;
    }

    public function skip(int $limit): BaseQueryRelateContract
    {
        $this->query->skip($limit);
        return $this;
    }

    public function take(int $limit): BaseQueryRelateContract
    {
        $this->query->take($limit);
        return $this;
    }

    public function groupBy(string $column): BaseQueryRelateContract
    {
        $this->query->groupBy($column);
        return $this;
    }

    public function groupByArray(array $columns): BaseQueryRelateContract
    {
        $this->query->groupBy($columns);
        return $this;
    }

    public function orderBy(string $column, string $sort = 'desc'): BaseQueryRelateContract
    {
        $this->query->orderBy($column,$sort);
        return $this;
    }

    public function orderByArray(array $columns): BaseQueryRelateContract
    {
        array_map(function($value,$key){
           $this->query->orderBy($key,$value);
        },$columns);
        return $this;
    }

    public function distinct(): BaseQueryRelateContract
    {
        // TODO: Implement distinct() method.
        $this->query->distinct();
        return $this;
    }

    public function where(string $column, string $operator = '=', string $value = ''): BaseQueryRelateContract
    {
        $this->query->where($column,$operator,$value);
        return $this;
    }

    public function orWhere(string $column, string $operator = '=', string $value = ''): BaseQueryRelateContract
    {
        $this->query->orWhere($column,$operator,$value);
        return $this;
    }

    public function whereBetween(string $column, array $between): BaseQueryRelateContract
    {
        $this->query->whereBetween($column,$between);
        return $this;
    }

    public function orWhereBetween(string $column, array $between): BaseQueryRelateContract
    {
        $this->query->orWhereBetween($column,$between);
        return $this;
    }

    public function whereRaw(string $sql, array $bindings = []): BaseQueryRelateContract
    {
        $this->query->whereRaw($sql,$bindings);
        return $this;
    }

    public function orWhereRaw(string $sql, array $bindings = []): BaseQueryRelateContract
    {
        $this->query->orWhereRaw($sql,$bindings);
        return $this;
    }

    public function orWhereNotBetween($column, array $between): BaseQueryRelateContract
    {
        $this->query->orWhereNotBetween($column,$between);
        return $this;
    }

    public function whereExists(\Closure $callback): BaseQueryRelateContract
    {
        $this->query->whereExists($callback);
        return $this;
    }
    public function orWhereExists(\Closure $callback): BaseQueryRelateContract
    {
        $this->query->orWhereExists($callback);
        return $this;
    }

    public function whereNotExists(\Closure $callback): BaseQueryRelateContract
    {
        $this->query->whereNotExists($callback);
        return $this;
    }

    public function orWhereNotExists(\Closure $callback): BaseQueryRelateContract
    {
        $this->query->orWhereNotExists($callback);
        return $this;
    }

    public function whereIn(string $column, array $values): BaseQueryRelateContract
    {
        $this->query->whereIn($column,$values);
        return $this;
    }

    public function orWhereIn(string $column, array $values): BaseQueryRelateContract
    {
        $this->query->orWhereIn($column,$values);
        return $this;
    }

    public function whereNotIn(string $column, array $values): BaseQueryRelateContract
    {
        $this->query->whereNotIn($column,$values);
        return $this;
    }

    public function orWhereNotIn(string $column, array $values): BaseQueryRelateContract
    {
        $this->query->orWhereNotIn($column,$values);
        return $this;
    }

    public function whereNull(string $column): BaseQueryRelateContract
    {
        $this->query->whereNull($column);
        return $this;
    }

    public function orWhereNull(string $column): BaseQueryRelateContract
    {
        $this->query->orWhereNull($column);
        return $this;
    }

    public function whereNotNull(string $column): BaseQueryRelateContract
    {
        $this->query->whereNotNull($column);
        return $this;
    }

    public function orWhereNotNull(string $column): BaseQueryRelateContract
    {
        $this->query->orWhereNotNull($column);
        return $this;
    }

    public function union(BaseQueryRelateContract $queryRelate): BaseQueryRelateContract
    {
        $this->query = $this->query->union($queryRelate->getQuery());
        return $this;
    }

    public function raw(string $sql): BaseQueryRelateContract
    {
        $this->query->raw($sql);
        return $this;
    }

    public function from(string $table): BaseQueryRelateContract
    {
        $this->query->raw($table);
        return $this;
    }
    public function join(string $table, string $one, string $operator = '=', string $two = ''): BaseQueryRelateContract
    {
        $this->query->join($table,$one,$operator,$two);
        return $this;
    }

    public function joinByClosure(string $table, \Closure $callback): BaseQueryRelateContract
    {
        $this->query->join($table,$callback);
        return $this;
    }

    public function leftJoin(string $table, string $first, string $operator = '=', string $two = ''): BaseQueryRelateContract
    {
        $this->query->leftjoin($table,$first,$operator,$two);
        return $this;
    }

    public function leftJoinByClosure(string $table, \Closure $callback): BaseQueryRelateContract
    {
        $this->query->leftjoin($table,$callback);
        return $this;
    }

    public function rightJoin(string $table, string $first, string $operator = '=', string $two = ''): BaseQueryRelateContract
    {
        $this->query->rightJoin($table,$first,$operator,$two);
        return $this;
    }

    public function rightJoinByClosure(string $table, \Closure $callback): BaseQueryRelateContract
    {
        $this->query->rightJoinByClosure($table,$callback);
        return $this;
    }

    public function callable(callable $callable): BaseQueryRelateContract
    {
        $this->query = call_user_func($callable,$this->query);
        return $this;
    }

    public function wheres(array $wheres): BaseQueryRelateContract
    {
        $this->query = (new ResolveWhereQuery)->getQuery($wheres,$this->query);
        return $this;
    }

    public function magic(QueryMagic $queryMagic): BaseQueryRelateContract
    {
        $this->query = $queryMagic->magic($this->query,$this->repository->getRepository())->getQuery();
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

        throw new MethodNotFoundException(static::class,$name);
    }

}