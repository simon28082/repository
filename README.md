## CRCMS Repository

[![Latest Stable Version](https://poser.pugx.org/crcms/repository/v/stable)](https://packagist.org/packages/crcms/repository)
[![License](https://poser.pugx.org/crcms/repository/license)](https://packagist.org/packages/crcms/repository)
[![StyleCI](https://github.styleci.io/repos/75898581/shield?branch=master)](https://github.styleci.io/repos/75898581)

A specialized data provider layer, based on ORM, only as a data provider, the main role is to separate the coupling before the Controller and Model

## Install

You can install the package via composer:

```bash
composer require crcms/repository
```

## Laravel

If your version is less than 5.5 please modify ``config / app.php``

```php
'providers' => [
    CrCms\Repository\RepositoryServiceProvider::class,
]

```

If you'd like to make configuration changes in the configuration file you can pubish it with the following Aritsan command:
```bash
php artisan vendor:publish --provider="CrCms\Repository\RepositoryServiceProvider"
```

## Commands

```bash
php artisan make:repository TestRepository --model TestModel
```
```bash
php artisan make:magic TestMagic
```

## Example

### QueryMagic
```php

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
    
    /**
     * @param QueryRelate $queryRelate
     * @param array $sort
     * @return QueryRelate
     */
    protected function bySort(QueryRelate $queryRelate, array $sort)
    {
        return $queryRelate->orderByArray($sort);
    }
}
```

### Repository
```php
class TestRepository extends AbstractRepository
{
    /**
     * @var array
     */
    protected $guard = [
        'id', 'title','other'
    ];

    /**
     * @return TestModel
     */
    public function newModel(): TestModel
    {
        return app(TestModel::class);
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(AbstractMagic $magic = null, int $perPage = 15): LengthAwarePaginator
    {
        return $this->whenMagic($magic)->where('built_in', 1)->orderBy($this->getModel()->getKeyName(), 'desc')->paginate($perPage);
    }

    /**
     * @param int $name
     * @param int $title
     */
    public function updateName(string $name, string $title)
    {
        $this->where('name', $name)->update(['title' => $title]);
    }
    
}
```

### Guard Or Scenes

Usually we need to filter the incoming parameter values when adding or modifying and querying the data, and retain the required parameter values.

Guard and scenes are born for this

```php
class TestRepository extends AbstractRepository
{
    /**
     * @var array
     */
    protected $scenes = [
        'create' => ['sort', 'added_at'],
        'modify' => ['sort', 'published_at']
    ];
    
    /**
     * @var array
     */
    protected $guard = [
        'id', 'title', 'other'
    ];
}

$testRepository->create($data, 'create'); //OR
$testRepository->setCurrentScene('create')->create($data); //OR
$testRepository->setGuard(['sort', 'added_at'])->create($data); 

```

```php
class TestMagic extends AbstractMagic
{
    /**
     * @var array
     */
    protected $scenes = [
        'frontend' => ['name'],
        'backend' => ['title']
    ];
    
    /**
     * @var array
     */
    protected $guard = [
        'title',
    ];

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
}

$testRepository->magic(new TestMagic($data, 'frontend'))->paginate(); //OR
$testRepository->magic((new TestMagic($data))->setCurrentScene('frontend'))->paginate(); //OR
$testRepository->magic((new TestMagic($data))->setGuard(['title']))->paginate(); //OR->create($data);

```

**Note: when guard and scenes are both present, guard has a higher priority. If guard is empty, it will use scenes.**

### Listener

```php
TestRepository::observer(TestListener::class);

TestListener {

    public function creating(TestRepository $repository, array $data)
    {
		//append the value to be written
		$repository->addData('append_data','value');
		
		//rewrite all values written
		$repository->setData(['key'=>'value']);
    }    
    
    public function created(TestRepository $repository, TestModel $model)
    {
    }    

    public function updating(TestRepository $repository, array $data)
    {
    }    
    
    public function updated(TestRepository $repository, TestModel $model)
    {
    }
    
    public function deleting(TestRepository $repository, array $ids)
    {
    }
    
    public function deleted(TestRepository $repository, Collection $models)
    {
    }
}
```

### Cache

```php
class TestRepository {

    public function do(User $user)
    {
        return $this->byIntId($user->id);
    }
}

$repository = new TestRepository;

```

#### store
```php
$repository->cache()->do(new User);
```

#### forget
```php
$repository->cache()->forget('do')
```

#### flush
```php
$repository->cache()->flush()
```


### Repository Methods
```php
public function all(): Collection;
```
```php
public function get(): Collection;
``` 
```php
public function pluck(string $column, string $key = ''): Collection;
```
```php
public function max(string $column): int;
```
```php
public function count(string $column = '*'): int;
```
```php
public function avg($column): int;
```
```php
public function sum(string $column): int;
```
```php
public function chunk(int $limit, callable $callback): bool;
```
```php
public function valueOfString(string $key, string $default = ''): string;
```
```php
public function valueOfInt(string $key, int $default = 0): int;
```
```php
public function increment(string $column, int $amount = 1, array $extra = []): int;
```
```php
public function decrement(string $column, int $amount = 1, array $extra = []): int;
```
```php
public function delete(): int;
```
```php
public function deleteByStringId(string $id): int;
```
```php
public function deleteByIntId(int $id): int;
```
```php
public function deleteByArray(array $ids): int;
```
```php
public function paginate(int $perPage = 15) : LengthAwarePaginator;
```
```php
public function create(array $data) : Model;
```
```php
public function update(array $data): int;
```
```php
public function updateByIntId(array $data, int $id) : Model;
```
```php
public function updateByStringId(array $data, string $id) : Model;
```
```php
public function byIntId(int $id);
```
```php
public function byStringId(string $id);
```
```php
public function byIntIdOrFail(int $id) : Model;
```
```php
public function byStringIdOrFail(string $id) : Model;
```
```php
public function oneByString(string $field, string $value): Model;
```
```php
public function oneByInt(string $field, int $value): Model;
```
```php
public function oneByStringOrFail(string $field, string $value) : Model;
```
```php
public function oneByIntOrFail(string $field, int $value) : Model;
```
```php
public function first();
```
```php
public function firstOrFail() : Model;
```    

### QueryRelate Methods

```php
public function select(array $column = ['*']): QueryRelate;
```
```php
public function selectRaw(string $expression, array $bindings = []): QueryRelate;
```
```php
public function skip(int $limit): QueryRelate;
```
```php
public function take(int $limit): QueryRelate;
```
```php
public function groupBy(string $column): QueryRelate;
```
```php
public function groupByArray(array $columns): QueryRelate;
```
```php
public function orderBy(string $column, string $sort = 'desc'): QueryRelate;
```
```php
public function orderByArray(array $columns): QueryRelate;
```
```php
public function distinct(): QueryRelate;
```
```php
public function where(string $column, string $operator = '=', string $value = ''): QueryRelate;
```
```php
public function whereClosure(\Closure $callback): QueryRelate;
```
```php
public function orWhereClosure(\Closure $callback): QueryRelate;
```
```php
public function orWhere(string $column, string $operator = '=', string $value = ''): QueryRelate;
```    
```php
public function whereBetween(string $column, array $between): QueryRelate;
```
```php
public function orWhereBetween(string $column, array $between): QueryRelate;
```    
```php
public function whereRaw(string $sql, array $bindings = []): QueryRelate;
```
```php
public function orWhereRaw(string $sql, array $bindings = []): QueryRelate;
```

```php
public function orWhereNotBetween($column, array $between): QueryRelate;
```    

```php
public function whereExists(\Closure $callback): QueryRelate;
```

```php
public function orWhereExists(\Closure $callback): QueryRelate;
```

```php
public function whereNotExists(\Closure $callback): QueryRelate;
```

```php
public function orWhereNotExists(\Closure $callback): QueryRelate;
```
```php
public function whereIn(string $column, array $values): QueryRelate;
```
```php
public function orWhereIn(string $column, array $values): QueryRelate;
```
```php
public function whereNotIn(string $column, array $values): QueryRelate;
```    

```php
public function orWhereNotIn(string $column, array $values): QueryRelate;
```    
```php
public function whereNull(string $column): QueryRelate;
```
```php
public function orWhereNull(string $column): QueryRelate;
```
```php
public function whereNotNull(string $column): QueryRelate;
```
```php
public function orWhereNotNull(string $column): QueryRelate;
```
```php
public function raw(string $sql): QueryRelate;
```    
```php
public function from(string $table): QueryRelate;
```
```php
public function join(string $table, string $one, string $operator = '=', string $two = ''): QueryRelate;
```
```php
public function joinClosure(string $table, \Closure $callback): QueryRelate;
```
```php
public function leftJoin(string $table, string $first, string $operator = '=', string $two = ''): QueryRelate;
```
```php
public function leftJoinClosure(string $table, \Closure $callback): QueryRelate;
```
```php
public function rightJoin(string $table, string $first, string $operator = '=', string $two = ''): QueryRelate;
```
```php
public function rightJoinClosure(string $table, \Closure $callback): QueryRelate;
```
```php
public function callable(callable $callable): QueryRelate;
```
```php
public function wheres(array $wheres): QueryRelate;
```    
```php
public function union(QueryRelate $queryRelate): QueryRelate;
```
```php
public function magic(QueryMagic $queryMagic): QueryRelate;
```
```php
public function whenMagic(?QueryMagic $queryMagic = null): QueryRelate;
```
```php
public function with(string $relation): QueryRelate;
```
```php
public function withArray(array $relations): QueryRelate;
```
```php
public function without(string $relation): QueryRelate;
```
```php
public function withoutArray(array $relations): QueryRelate;
```
```php
public function having(string $column, $operator = null, $value = null): QueryRelate;
```
```php
public function orHaving(string $column, $operator = null, $value = null): QueryRelate;
```
```php
public function havingRaw(string $sql, array $bindings = []): QueryRelate;
```
```php
public function orHavingRaw(string $sql, array $bindings = []): QueryRelate;
```
```php
public function lockForUpdate(): QueryRelate;
```
```php
public function sharedLock(): QueryRelate;
```


## License
[MIT license](https://opensource.org/licenses/MIT)
