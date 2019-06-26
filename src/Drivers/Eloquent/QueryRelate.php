<?php

namespace CrCms\Repository\Drivers\Eloquent;

use CrCms\Repository\Contracts\QueryMagic;
use CrCms\Repository\Contracts\QueryRelate as BaseQueryRelateContract;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Drivers\QueryRelate as BaseQueryRelate;
use CrCms\Repository\Exceptions\MethodNotFoundException;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class QueryRelate.
 */
class QueryRelate extends BaseQueryRelate implements BaseQueryRelateContract
{
    /**
     * QueryRelate constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->setRepository($repository);
    }

    /**
     * @return Builder
     */
    public function getQuery(): object
    {
        return $this->query;
    }

    /**
     * @param Builder $query
     *
     * @return BaseQueryRelate
     */
    public function setQuery(Builder $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @param array $column
     *
     * @return BaseQueryRelateContract
     */
    public function select(array $column = ['*']): BaseQueryRelateContract
    {
        $this->query->select($column);

        return $this;
    }

    /**
     * @param string $expression
     * @param array  $bindings
     *
     * @return BaseQueryRelateContract
     */
    public function selectRaw(string $expression, array $bindings = []): BaseQueryRelateContract
    {
        $this->query->selectRaw($expression, $bindings);

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return BaseQueryRelateContract
     */
    public function skip(int $limit): BaseQueryRelateContract
    {
        $this->query->skip($limit);

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return BaseQueryRelateContract
     */
    public function take(int $limit): BaseQueryRelateContract
    {
        $this->query->take($limit);

        return $this;
    }

    /**
     * @param string $column
     *
     * @return BaseQueryRelateContract
     */
    public function groupBy(string $column): BaseQueryRelateContract
    {
        $this->query->groupBy($column);

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return BaseQueryRelateContract
     */
    public function groupByArray(array $columns): BaseQueryRelateContract
    {
        $this->query->groupBy($columns);

        return $this;
    }

    /**
     * @param string $column
     * @param string $sort
     *
     * @return BaseQueryRelateContract
     */
    public function orderBy(string $column, string $sort = 'desc'): BaseQueryRelateContract
    {
        $this->query->orderBy($column, $sort);

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return BaseQueryRelateContract
     */
    public function orderByArray(array $columns): BaseQueryRelateContract
    {
        array_walk($columns, function ($value, $key) {
            $this->query->orderBy($key, $value);
        });

        return $this;
    }

    /**
     * @param string $sql
     * @param array  $bindings
     *
     * @return BaseQueryRelateContract
     */
    public function orderByRaw(string $sql, array $bindings = []): BaseQueryRelateContract
    {
        $this->query->orderByRaw($sql, $bindings);

        return $this;
    }

    /**
     * @return BaseQueryRelateContract
     */
    public function distinct(): BaseQueryRelateContract
    {
        // TODO: Implement distinct() method.
        $this->query->distinct();

        return $this;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param mixed  $value
     *
     * @return BaseQueryRelateContract
     */
    public function where(string $column, $operator = null, $value = null): BaseQueryRelateContract
    {
        $this->query->where($column, $operator, $value);

        return $this;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param mixed  $value
     *
     * @return BaseQueryRelateContract
     */
    public function orWhere(string $column, $operator = null, $value = null): BaseQueryRelateContract
    {
        $this->query->orWhere($column, $operator, $value);

        return $this;
    }

    /**
     * @param \Closure $callback
     *
     * @return BaseQueryRelateContract
     */
    public function whereClosure(\Closure $callback): BaseQueryRelateContract
    {
        $this->query->where($callback);

        return $this;
    }

    /**
     * @param \Closure $callback
     *
     * @return BaseQueryRelateContract
     */
    public function orWhereClosure(\Closure $callback): BaseQueryRelateContract
    {
        $this->query->orWhere($callback);

        return $this;
    }

    /**
     * @param string $column
     * @param array  $between
     *
     * @return BaseQueryRelateContract
     */
    public function whereBetween(string $column, array $between): BaseQueryRelateContract
    {
        $this->query->whereBetween($column, $between);

        return $this;
    }

    /**
     * @param string $column
     * @param array  $between
     *
     * @return BaseQueryRelateContract
     */
    public function orWhereBetween(string $column, array $between): BaseQueryRelateContract
    {
        $this->query->orWhereBetween($column, $between);

        return $this;
    }

    /**
     * @param string $sql
     * @param array  $bindings
     *
     * @return BaseQueryRelateContract
     */
    public function whereRaw(string $sql, array $bindings = []): BaseQueryRelateContract
    {
        $this->query->whereRaw($sql, $bindings);

        return $this;
    }

    /**
     * @param string $sql
     * @param array  $bindings
     *
     * @return BaseQueryRelateContract
     */
    public function orWhereRaw(string $sql, array $bindings = []): BaseQueryRelateContract
    {
        $this->query->orWhereRaw($sql, $bindings);

        return $this;
    }

    /**
     * @param $column
     * @param array $between
     *
     * @return BaseQueryRelateContract
     */
    public function orWhereNotBetween($column, array $between): BaseQueryRelateContract
    {
        $this->query->orWhereNotBetween($column, $between);

        return $this;
    }

    /**
     * @param \Closure $callback
     *
     * @return BaseQueryRelateContract
     */
    public function whereExists(\Closure $callback): BaseQueryRelateContract
    {
        $this->query->whereExists($callback);

        return $this;
    }

    /**
     * @param \Closure $callback
     *
     * @return BaseQueryRelateContract
     */
    public function orWhereExists(\Closure $callback): BaseQueryRelateContract
    {
        $this->query->orWhereExists($callback);

        return $this;
    }

    /**
     * @param \Closure $callback
     *
     * @return BaseQueryRelateContract
     */
    public function whereNotExists(\Closure $callback): BaseQueryRelateContract
    {
        $this->query->whereNotExists($callback);

        return $this;
    }

    /**
     * @param \Closure $callback
     *
     * @return BaseQueryRelateContract
     */
    public function orWhereNotExists(\Closure $callback): BaseQueryRelateContract
    {
        $this->query->orWhereNotExists($callback);

        return $this;
    }

    /**
     * @param string $column
     * @param array  $values
     *
     * @return BaseQueryRelateContract
     */
    public function whereIn(string $column, array $values): BaseQueryRelateContract
    {
        $this->query->whereIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array  $values
     *
     * @return BaseQueryRelateContract
     */
    public function orWhereIn(string $column, array $values): BaseQueryRelateContract
    {
        $this->query->orWhereIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array  $values
     *
     * @return BaseQueryRelateContract
     */
    public function whereNotIn(string $column, array $values): BaseQueryRelateContract
    {
        $this->query->whereNotIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array  $values
     *
     * @return BaseQueryRelateContract
     */
    public function orWhereNotIn(string $column, array $values): BaseQueryRelateContract
    {
        $this->query->orWhereNotIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     *
     * @return BaseQueryRelateContract
     */
    public function whereNull(string $column): BaseQueryRelateContract
    {
        $this->query->whereNull($column);

        return $this;
    }

    /**
     * @param string $column
     *
     * @return BaseQueryRelateContract
     */
    public function orWhereNull(string $column): BaseQueryRelateContract
    {
        $this->query->orWhereNull($column);

        return $this;
    }

    /**
     * @param string $column
     *
     * @return BaseQueryRelateContract
     */
    public function whereNotNull(string $column): BaseQueryRelateContract
    {
        $this->query->whereNotNull($column);

        return $this;
    }

    /**
     * @param string $column
     *
     * @return BaseQueryRelateContract
     */
    public function orWhereNotNull(string $column): BaseQueryRelateContract
    {
        $this->query->orWhereNotNull($column);

        return $this;
    }

    /**
     * @param $column
     * @param null $operator
     * @param null $value
     *
     * @return BaseQueryRelateContract
     */
    public function having($column, $operator = null, $value = null): BaseQueryRelateContract
    {
        $this->query->having($column, $operator, $value, 'and');

        return $this;
    }

    /**
     * @param $column
     * @param null $operator
     * @param null $value
     *
     * @return BaseQueryRelateContract
     */
    public function orHaving($column, $operator = null, $value = null): BaseQueryRelateContract
    {
        $this->query->orHaving($column, $operator, $value);

        return $this;
    }

    /**
     * @param string $sql
     * @param array  $bindings
     *
     * @return BaseQueryRelateContract
     */
    public function havingRaw(string $sql, array $bindings = []): BaseQueryRelateContract
    {
        $this->query->havingRaw($sql, $bindings, 'and');

        return $this;
    }

    /**
     * @param string $sql
     * @param array  $bindings
     *
     * @return BaseQueryRelateContract
     */
    public function orHavingRaw(string $sql, array $bindings = []): BaseQueryRelateContract
    {
        $this->query->orHavingRaw($sql, $bindings);

        return $this;
    }

    /**
     * @param BaseQueryRelateContract $queryRelate
     * @param bool                    $unionAll
     *
     * @return BaseQueryRelateContract
     */
    public function union(BaseQueryRelateContract $queryRelate, bool $unionAll = true): BaseQueryRelateContract
    {
        $this->query = $this->query->union($queryRelate->getQuery(), $unionAll);

        return $this;
    }

    /**
     * @param string $sql
     *
     * @return BaseQueryRelateContract
     */
    public function raw(string $sql): BaseQueryRelateContract
    {
        $this->query->raw($sql);

        return $this;
    }

    /**
     * @param string $table
     *
     * @return BaseQueryRelateContract
     */
    public function from(string $table): BaseQueryRelateContract
    {
        $this->query->raw($table);

        return $this;
    }

    /**
     * @param string $table
     * @param string $one
     * @param string $operator
     * @param string $two
     *
     * @return BaseQueryRelateContract
     */
    public function join(string $table, string $one, string $operator = '=', string $two = ''): BaseQueryRelateContract
    {
        $this->query->join($table, $one, $operator, $two);

        return $this;
    }

    /**
     * @param string   $table
     * @param \Closure $callback
     *
     * @return BaseQueryRelateContract
     */
    public function joinClosure(string $table, \Closure $callback): BaseQueryRelateContract
    {
        $this->query->join($table, $callback);

        return $this;
    }

    /**
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $two
     *
     * @return BaseQueryRelateContract
     */
    public function leftJoin(string $table, string $first, string $operator = '=', string $two = ''): BaseQueryRelateContract
    {
        $this->query->leftjoin($table, $first, $operator, $two);

        return $this;
    }

    /**
     * @param string   $table
     * @param \Closure $callback
     *
     * @return BaseQueryRelateContract
     */
    public function leftJoinClosure(string $table, \Closure $callback): BaseQueryRelateContract
    {
        $this->query->leftjoin($table, $callback);

        return $this;
    }

    /**
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $two
     *
     * @return BaseQueryRelateContract
     */
    public function rightJoin(string $table, string $first, string $operator = '=', string $two = ''): BaseQueryRelateContract
    {
        $this->query->rightJoin($table, $first, $operator, $two);

        return $this;
    }

    /**
     * @param string   $table
     * @param \Closure $callback
     *
     * @return BaseQueryRelateContract
     */
    public function rightJoinClosure(string $table, \Closure $callback): BaseQueryRelateContract
    {
        $this->query->rightJoin($table, $callback);

        return $this;
    }

    /**
     * @param callable $callable
     *
     * @return BaseQueryRelateContract
     */
    public function callable(callable $callable): BaseQueryRelateContract
    {
        $query = call_user_func($callable, $this->query);
        if ($query instanceof Builder) {
            $this->query = $query;
        }

        return $this;
    }

    /**
     * @param array $array
     *
     * @return BaseQueryRelateContract
     */
    public function whereArray(array $array): BaseQueryRelateContract
    {
        $this->query = (new ResolveWhereQuery())->getQuery($array, $this->query);

        return $this;
    }

    /**
     * @param QueryMagic $queryMagic
     *
     * @return BaseQueryRelateContract
     */
    public function magic(QueryMagic $queryMagic): BaseQueryRelateContract
    {
        $this->query = $queryMagic->magic($this, $this->repository->getRepository())->getQuery();

        return $this;
    }

    /**
     * @param QueryMagic|null $queryMagic
     *
     * @return BaseQueryRelateContract
     */
    public function whenMagic(?QueryMagic $queryMagic = null): BaseQueryRelateContract
    {
        if ($queryMagic instanceof QueryMagic) {
            return $this->magic($queryMagic);
        }

        return $this;
    }

    /**
     * @param bool     $condition
     * @param callable $trueCallable
     * @param callable $falseCallable
     *
     * @return BaseQueryRelateContract
     */
    public function when(bool $condition, callable $trueCallable, ?callable $falseCallable = null): BaseQueryRelateContract
    {
        if ($condition) {
            $this->query = call_user_func($trueCallable, $this)->getQuery();
        } elseif ($falseCallable) {
            $this->query = call_user_func($falseCallable, $this)->getQuery();
        } else {

        }

        return $this;
    }

    /**
     * @param array $conditions
     * @param array $callables
     *
     * @return BaseQueryRelateContract
     */
    public function whenMultiple(array $conditions, array $callables): BaseQueryRelateContract
    {
        array_map(function ($key) use ($callables) {
            $this->query = call_user_func($callables[$key], $this)->getQuery();
        }, array_intersect($conditions, array_keys($callables)));

        return $this;
    }

    /**
     * @param array $relations
     *
     * @return BaseQueryRelateContract
     */
    public function withArray(array $relations): BaseQueryRelateContract
    {
        $this->query->with($relations);

        return $this;
    }

    /**
     * @param string $relation
     *
     * @return BaseQueryRelateContract
     */
    public function with(string $relation): BaseQueryRelateContract
    {
        $this->query->with($relation);

        return $this;
    }

    /**
     * @param array $relations
     *
     * @return BaseQueryRelateContract
     */
    public function withoutArray(array $relations): BaseQueryRelateContract
    {
        $this->query->without($relations);

        return $this;
    }

    /**
     * @param string $relation
     *
     * @return BaseQueryRelateContract
     */
    public function without(string $relation): BaseQueryRelateContract
    {
        $this->query->without($relation);

        return $this;
    }

    /**
     * lockForUpdate.
     *
     * @return BaseQueryRelateContract
     */
    public function lockForUpdate(): BaseQueryRelateContract
    {
        $this->query->lockForUpdate();

        return $this;
    }

    /**
     * sharedLock.
     *
     * @return BaseQueryRelateContract
     */
    public function sharedLock(): BaseQueryRelateContract
    {
        $this->query->sharedLock();

        return $this;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return $this|mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->repository, $name)) {
            return call_user_func_array([$this->repository, $name], $arguments);
        }

        try {
            $query = call_user_func_array([$this->query, $name], $arguments);
            if ($query instanceof $this->query) {
                $this->query = $query;

                return $this;
            }

            return $query;
        } catch (\Exception $e) {
            throw new MethodNotFoundException(static::class, $name, $e);
        }
    }
}
