<?php
namespace CrCms\Repository\Eloquent;

use CrCms\Repository\Contracts\Eloquent\Eloquent;
use CrCms\Repository\Contracts\QueryRelate;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Exceptions\ResourceDeleteException;
use CrCms\Repository\Exceptions\ResourceNotFoundException;
use CrCms\Repository\Exceptions\ResourceStoreException;
use CrCms\Repository\Exceptions\ResourceUpdateException;
use CrCms\Repository\Listener\RepositoryListener;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractRepository implements Repository,Eloquent
{

    /**
     * @var Model
     */
    protected $model = null;

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var null
     */
    protected $queryRelate = null;


    protected $events = [];



    public function __construct()
    {
        $this->setQueryRelate($this->newDefaultQueryRelate());
    }

    /**
     * @return Model
     */
    abstract public function newModel() : Model;

    public function newQuery() : Builder
    {
        return $this->getModel()->newQuery();
    }

//    public function setQuery(Builder $query) : AbstractRepository
//    {
//        $this->query = $query;
//        return $this;
//    }


    public function newQueryRelate(Builder $query) : QueryRelate
    {
        return new \CrCms\Repository\Eloquent\QueryRelate($query,$this);
    }


    /**
     * 此方法适用于 一直执行QueryRelate中的方法，直到获取结果
     * $this->newDefaultQueryRelate()->where()->where()->pluck()
     * 注：并不适用于执行了QueryRelate中的方法再次执行$this中的方法
     * @return QueryRelate
     */
    public function newDefaultQueryRelate() : QueryRelate
    {
        return $this->newQueryRelate($this->newQuery());
    }


    public function getQueryRelate() : QueryRelate
    {
        return $this->queryRelate;
    }

    public function setQueryRelate(QueryRelate $queryRelate) : AbstractRepository
    {
        $this->queryRelate = $queryRelate;
        return $this;
    }


    public function resetQueryRelate() : AbstractRepository
    {
        return $this->setQueryRelate($this->queryRelate->setQuery($this->newQuery()));
    }


    public function setNewQueryRelate()
    {
        $this->queryRelate = $this->newDefaultQueryRelate();
        return $this;
    }


    protected function eventDispatcher(string $event)
    {
        new RepositoryListener();
    }


    /**
     * @return Model
     */
    public function getModel() : Model
    {
        if (!$this->model) {
            $this->model = $this->newModel();
        }
        return $this->model;
    }

    public function setFillable(array $fillable) : AbstractRepository
    {
        $this->fillable = $fillable;
        return $this;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function fillableFilter(array $data) : array
    {
        if (empty($this->fillable)) return $data;

        return array_filter($data,function($key) {
            return in_array($key,$this->fillable,true);
        },ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        $data = $this->fillableFilter($data);

        try {
            return $this->getModel()->create($data);
        } catch (\Exception $exception) {
            throw new ResourceStoreException($exception->getMessage());
        }
    }


    /**
     * @param array $data
     * @param int|string $id
     * @return Model
     */
    public function update(array $data, $id): Model
    {
        $data = $this->fillableFilter($data);

        $model = $this->byId($id);

        foreach ($data as $key=>$value) {
            $model->{$key} = $value;
        }

        try {
            $model->save();
        } catch (\Exception $exception) {
            throw new ResourceUpdateException($exception->getMessage());
        }

        return $model;
    }

    public function updateByIntId(array $data, int $id): Model
    {
        return $this->update($data,$id);
    }

    public function updateByStringId(array $data, string $id): Model
    {
        return $this->update($data,$id);
    }


    /**
     * @param int $id
     * @return int
     */
    public function delete( $id): int
    {
        try {
            $rows = $this->queryRelate->where('id',$id)->delete();
        } catch (\Exception $exception) {
            throw new ResourceDeleteException($exception->getMessage());
        } finally {
            $this->resetQueryRelate();
        }

        return $rows;
    }

    public function deleteByStringId(string $id): int
    {
        return $this->delete($id);
    }

    public function deleteByIntId(int $id): int
    {
        return $this->delete($id);
    }


    /**
     * @param array $ids
     * @return int
     */
    public function deleteByArray(array $ids) : int
    {
        try {
            return $this->newDefaultQueryRelate()->whereIn('id',$ids)->delete();
        } catch (\Exception $exception) {
            throw new ResourceDeleteException($exception->getMessage());
        }
    }


    public function paginate(int $perPage = 15): LengthAwarePaginator
     {
         $paginate = $this->queryRelate->orderBy($this->getModel()->getKeyName(),'desc')->getQuery()->paginate($perPage);

         $this->resetQueryRelate();

         return $paginate;
     }




    protected function byId($id)
    {
        //在QueryRelate 中的 __call中也没有找到first，需要用getQuery()，它指向的是Query\Builder
        $model = $this->queryRelate->where($this->getModel()->getKeyName(),$id)->getQuery()->first();

        $this->resetQueryRelate();

        return $model;
    }

    protected function byIdOrFail($id)
    {
        $model = $this->byId($id);
        if (empty($model)) {
            throw new ResourceNotFoundException();
        }
        return $model;
    }

    public function byIntId(int $id)
    {
        return $this->byId($id);
    }

    public function byStringId(string $id)
    {
        return $this->byId($id);
    }

    public function byIntIdOrFail(int $id): Model
    {
        return $this->byIdOrFail($id);
    }

    public function byStringIdOrFail(string $id): Model
    {
        return $this->byIdOrFail($id);
    }

    public function oneByString(string $field, string $value)
    {
        return $this->oneBy($field,$value);
    }

    public function oneByInt(string $field, int $value)
    {
        return $this->oneBy($field,$value);
    }

    public function oneByStringOrFail(string $field, string $value): Model
    {
        return $this->oneByOrFail($field,$value);
    }

    public function oneByIntOrFail(string $field, int $value): Model
    {
        return $this->oneByOrFail($field,$value);
    }



     public function oneBy(string $field,  $value)
     {
         $model =  $this->queryRelate->where($field,$value)->getQuery()->first();

         $this->resetQueryRelate();

         return $model;
     }

     public function oneByOrFail(string $field,  $value): Model
     {
         $model = $this->oneBy($field,$value);
         if (empty($model)) {
             throw new ResourceNotFoundException();
         }
         return $model;
     }

     public function first()
     {
         $model = $this->queryRelate->getQuery()->first();

         $this->resetQueryRelate();

         return $model;
     }

     public function firstOrFail(): Model
     {
         $model = $this->first();
         if (empty($model)) {
             throw new ResourceNotFoundException();
         }
         return $model;
     }

     public function all(): Collection
     {
         //all表示不加任何条件，必须重新设置query
         $models = $this->queryRelate->setQuery($this->newQuery())->getQuery()->get();

         $this->resetQueryRelate();

         return $models;
     }

     public function get(): Collection
     {
        $models = $this->queryRelate->getQuery()->get();

        $this->resetQueryRelate();

        return $models;
     }

     public function pluck(string $column, string $key = null): Collection
     {
         $models = $this->queryRelate->getQuery()->pluck($column,$key);

         $this->resetQueryRelate();

         return $models;
     }

     public function max(string $column): int
     {
         $max = $this->queryRelate->getQuery()->max($column);

         $this->resetQueryRelate();

         return $max;
     }

     public function count(string $column = '*'): int
     {
         $count = $this->queryRelate->getQuery()->count($column);

         $this->resetQueryRelate();

         return $count;
     }

     public function avg($column): int
     {
         $avg = $this->queryRelate->getQuery()->avg($column);
         $this->resetQueryRelate();
         return $avg;
     }

     public function sum(string $column): int
     {
         $sum = $this->queryRelate->getQuery()->sum($column);

         $this->resetQueryRelate();

         return $sum;
     }

    /**
     * chunk reset model有问题需要尝试
     * @param int $limit
     * @param callable $callback
     * @return mixed
     */
     public function chunk(int $limit, callable $callback) : bool
     {
         $result = $this->queryRelate->getQuery()->chunk($limit,$callback);

         $this->resetQueryRelate();

         return $result;
     }

     public function value(string $key)
     {
         $value = $this->queryRelate->getQuery()->value($key);

         $this->resetQueryRelate();

         return $value;
     }

     public function increment(string $column, int $amount = 1, array $extra = []) : int
     {
         $rows = $this->queryRelate->getQuery()->increment($column,$amount,$extra);
         $this->resetQueryRelate();
         return $rows;
     }

     public function decrement(string $column, int $amount = 1, array $extra = []) : int
     {
         $rows = $this->queryRelate->getQuery()->decrement($column,$amount,$extra);
         $this->resetQueryRelate();
         return $rows;
     }


    protected function fireEvent(string $event)
    {
        $result = $this->fireCustomEvent($event);

        if ($result === false) {
            return false;
        }

        if (empty($result)) {
            $className = static::class.'Event';
            if (class_exists($className)) {
                $eventObject = new $className($this);
                if (method_exists($eventObject,$event)) {
                    $result = $eventObject->{$event}();
                    if ($result === false) {
                        return false;
                    }
                }
            } else {
                //$eventObject = new RepositoryEvent($this);

            }
        }

        return $result;
    }


    protected function fireCustomEvent(string $event)
    {
        if (! isset($this->events[$event])) {
            return;
        }

        $result = event(new $this->events[$event]($this));

        if (! is_null($result)) {
            return $result;
        }

        return;
    }

     protected function event()
     {
         $allEvent = ['creating','created','updating','updated','deleting','deleted'];
     }



    public function __call($name, $arguments)
    {
        //当直接调用QueryRelate时，则开启新的
        //$this->setNewQueryRelate();
        if (method_exists($this->queryRelate,$name)) {
            $result = call_user_func_array([$this->queryRelate,$name],$arguments);
            if ($result instanceof $this->queryRelate) {
                $this->setQueryRelate($result);
                return $this;
            }
            return $result;
        }
    }
}