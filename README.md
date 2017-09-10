## Example
```
class TestRepository extends AbstractRepository
{
    /**
     * @var array
     */
    protected $guard = [
        'id', 'title','other'
    ];

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function newModel()
    {
        return app(Test::class);
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(AbstractMagic $magic = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->where('built_in', 1);

        if ($magic) {
            $query->magic($magic);
        }

        return $query->orderBy($this->getModel()->getKeyName(), 'desc')->paginate($perPage);
    }

    /**
     * @param int $name
     * @param int $title
     */
    public function updateName(string $name, string $title)
    {
        $this->getModel()->where('name', $name)->update(['title' => $title]);
    }
    
}
```

## QueryMagic
```

use CrCms\Repository\AbstractMagic;
use CrCms\Repository\Contracts\QueryRelate;

class TestMagic extends AbstractMagic
{
    /**
     * @param QueryRelate $queryRelate
     * @param int $id
     * @return QueryRelate
     */
    protected function byName(QueryRelate $queryRelate, string $name)
    {
        return $queryRelate->where('name', $name);
    }

    /**
     * @param QueryRelate $queryRelate
     * @param string $title
     * @return QueryRelate
     */
    protected function byTitle(QueryRelate $queryRelate, string $title)
    {
        return $queryRelate->where('title', 'like', "%{$title}%");
    }

    /**
     * @param QueryRelate $queryRelate
     * @param int $id
     * @return QueryRelate
     */
    protected function byId(QueryRelate $queryRelate, int $id)
    {
        return $queryRelate->where('id', $id);
    }
}
```

## Listener
```
TestRepository::observer(TestListener::class);

TestListener {

    public function creating()
    {
    }    
    
    public function created($repository,$model)
    {
    }    

    public function updating()
    {
    }    
    
    public function updated($repository,$model)
    {
    }
    
    public function deleting()
    {
    }
    
    public function deleted($repository,$model)
    {
    }
}
```

## Repository Methods
```
public function all(): Collection;
```
```
public function get(): Collection;
``` 
```
public function pluck(string $column, string $key = ''): Collection;
```
```
public function max(string $column): int;
```
```
public function count(string $column = '*'): int;
```
```
public function avg($column): int;
```
```
public function sum(string $column): int;
```
```
public function chunk(int $limit, callable $callback): bool;
```
```
public function valueOfString(string $key, string $default = ''): string;
```
```
public function valueOfInt(string $key, int $default = 0): int;
```
```
public function increment(string $column, int $amount = 1, array $extra = []): int;
```
```
public function decrement(string $column, int $amount = 1, array $extra = []): int;
```
```
public function delete(): int;
```
```
public function deleteByStringId(string $id): int;
```
```
public function deleteByIntId(int $id): int;
```
```
public function deleteByArray(array $ids): int;
```
```
public function paginate(int $perPage = 15) : LengthAwarePaginator;
```
```
public function create(array $data) : Model;
```
```
public function update(array $data): int;
```
```
public function updateByIntId(array $data, int $id) : Model;
```
```
public function updateByStringId(array $data, string $id) : Model;
```
```
public function byIntId(int $id);
```
```
public function byStringId(string $id);
```
```
public function byIntIdOrFail(int $id) : Model;
```
```
public function byStringIdOrFail(string $id) : Model;
```
```
public function oneByString(string $field, string $value): Model;
```
```
public function oneByInt(string $field, int $value): Model;
```
```
public function oneByStringOrFail(string $field, string $value) : Model;
```
```
public function oneByIntOrFail(string $field, int $value) : Model;
```
```
public function first();
```
```
public function firstOrFail() : Model;
```    

## QueryRelate

```
public function select(array $column = ['*']): QueryRelate;
```
```
public function selectRaw(string $expression, array $bindings = []): QueryRelate;
```
```
public function skip(int $limit): QueryRelate;
```
```
public function take(int $limit): QueryRelate;
```
```
public function groupBy(string $column): QueryRelate;
```
```
public function groupByArray(array $columns): QueryRelate;
```
```
public function orderBy(string $column, string $sort = 'desc'): QueryRelate;
```
```
public function orderByArray(array $columns): QueryRelate;
```
```
public function distinct(): QueryRelate;
```
```
public function where(string $column, string $operator = '=', string $value = ''): QueryRelate;
```
```
public function whereClosure(\Closure $callback): QueryRelate;

```
```
public function orWhereClosure(\Closure $callback): QueryRelate;

```
```
public function orWhere(string $column, string $operator = '=', string $value = ''): QueryRelate;
```    
```
public function whereBetween(string $column, array $between): QueryRelate;
```
```
public function orWhereBetween(string $column, array $between): QueryRelate;
```    
```
public function whereRaw(string $sql, array $bindings = []): QueryRelate;
```
```
public function orWhereRaw(string $sql, array $bindings = []): QueryRelate;
```

```
public function orWhereNotBetween($column, array $between): QueryRelate;
```    

```
public function whereExists(\Closure $callback): QueryRelate;
```

```
public function orWhereExists(\Closure $callback): QueryRelate;
```

```
public function whereNotExists(\Closure $callback): QueryRelate;
```

```
public function orWhereNotExists(\Closure $callback): QueryRelate;
```
```
public function whereIn(string $column, array $values): QueryRelate;
```
```
public function orWhereIn(string $column, array $values): QueryRelate;
```
```
public function whereNotIn(string $column, array $values): QueryRelate;
```    

```
public function orWhereNotIn(string $column, array $values): QueryRelate;
```    

```
public function whereNull(string $column): QueryRelate;
```

```
public function orWhereNull(string $column): QueryRelate;
```

```
public function whereNotNull(string $column): QueryRelate;
```

```
public function orWhereNotNull(string $column): QueryRelate;
```

```
public function raw(string $sql): QueryRelate;
```    

```
public function from(string $table): QueryRelate;
```

```
public function join(string $table, string $one, string $operator = '=', string $two = ''): QueryRelate;
```

```
public function joinByClosure(string $table, \Closure $callback): QueryRelate;
```

```
public function leftJoin(string $table, string $first, string $operator = '=', string $two = ''): QueryRelate;
```

```
public function leftJoinByClosure(string $table, \Closure $callback): QueryRelate;
```

```
public function rightJoin(string $table, string $first, string $operator = '=', string $two = ''): QueryRelate;
```

```
public function rightJoinByClosure(string $table, \Closure $callback): QueryRelate;
```

```
public function callable(callable $callable): QueryRelate;
```

```
public function wheres(array $wheres): QueryRelate;
```    

```
public function union(QueryRelate $queryRelate): QueryRelate;
```

```
public function magic(QueryMagic $queryMagic): QueryRelate;
```
    