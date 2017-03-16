<?php
namespace CrCms\Repository\Eloquent;

use CrCms\Repository\Contracts\Eloquent\Eloquent;
use CrCms\Repository\Contracts\QueryRelate;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Exceptions\MethodNotFoundException;
use CrCms\Repository\Facades\Event;
use CrCms\Repository\Exceptions\ResourceDeleteException;
use CrCms\Repository\Exceptions\ResourceNotFoundException;
use CrCms\Repository\Exceptions\ResourceStoreException;
use CrCms\Repository\Exceptions\ResourceUpdateException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class AbstractRepository
 * @package CrCms\Repository\Eloquent
 */
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

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $events = [
//        'created'=>Listener::class,
//        'creating'=>[Listener::class],
//        'updated'=>[
//            Listener::class,
//        ],
        'creating'=>[],
        'created'=>[],
        'updating'=>[],
        'updated'=>[],
        'deleting'=>[],
        'deleted'=>[]
    ];


    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }


    /**
     * @return array
     */
    public function getData() : array
    {
        return $this->data;
    }


    /**
     * AbstractRepository constructor.
     */
    public function __construct()
    {
        $this->setQueryRelate($this->newQueryRelate($this->newQuery()));
        $this->eventListen();
    }


    /**
     * @return Model
     */
    abstract public function newModel() : Model;


    /**
     * @return Builder
     */
    public function newQuery() : Builder
    {
        return $this->getModel()->newQuery();
    }


    /**
     * @param Builder $query
     * @return QueryRelate
     */
    public function newQueryRelate(Builder $query) : QueryRelate
    {
        return new \CrCms\Repository\Eloquent\QueryRelate($query,$this);
    }


    /**
     * @return QueryRelate
     */
    public function getQueryRelate() : QueryRelate
    {
        return $this->queryRelate;
    }


    /**
     * @param QueryRelate $queryRelate
     * @return AbstractRepository
     */
    public function setQueryRelate(QueryRelate $queryRelate) : AbstractRepository
    {
        $this->queryRelate = $queryRelate;
        return $this;
    }


    /**
     * @return AbstractRepository
     */
    public function resetQueryRelate() : AbstractRepository
    {
        return $this->setQueryRelate($this->queryRelate->setQuery($this->newQuery()));
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


    /**
     * @param array $fillable
     * @return AbstractRepository
     */
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
        $this->setData($this->fillableFilter($data));

        //这里是中断，要不要返回一个空模型，要思考
        if ($this->fireEvent('creating') === false) {
            return $this->newModel();
        }

        try {
            $model =  $this->getModel()->create($this->data);

            $this->fireEvent('created',$model);

            return $model;
        } catch (\Exception $exception) {
            throw new ResourceStoreException($exception->getMessage());
        }
    }


    /**
     * @param array $data
     * @param int|string $id
     * @return Model
     */
    protected function update(array $data, $id): Model
    {
        $this->setData($this->fillableFilter($data));

        if ($this->fireEvent('updating') === false) {
            return $this->getModel();
        }

        $model = $this->byId($id);

        array_walk($this->data,function ($value,$key) use ($model){
            $model->{$key} = $value;
        });

        try {
            $model->save();

            $this->fireEvent('updated',$model);

        } catch (\Exception $exception) {
            throw new ResourceUpdateException($exception->getMessage());
        }
        return $model;
    }

    /**
     * @param array $data
     * @param int $id
     * @return Model
     */
    public function updateByIntId(array $data, int $id): Model
    {
        return $this->update($data,$id);
    }

    /**
     * @param array $data
     * @param string $id
     * @return Model
     */
    public function updateByStringId(array $data, string $id): Model
    {
        return $this->update($data,$id);
    }


    /**
     * @param int $id
     * @return int
     */
    protected function delete($id): int
    {
        $id = (array)$id;
        $this->setData($id);

        $rows = 0;

        try {

            if ($this->fireEvent('deleting') === false) {
                return $rows;
            }

            $rows = $this->queryRelate->whereIn('id',$id)->getQuery()->delete();

            $this->fireEvent('deleted');

        } catch (\Exception $exception) {
            throw new ResourceDeleteException($exception->getMessage());
        } finally {
            $this->resetQueryRelate();
        }

        return $rows;
    }

    /**
     * @param string $id
     * @return int
     */
    public function deleteByStringId(string $id): int
    {
        return $this->delete($id);
    }

    /**
     * @param int $id
     * @return int
     */
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
        return $this->delete($ids);
    }


    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
     {
         $paginate = $this->queryRelate->orderBy($this->getModel()->getKeyName(),'desc')->getQuery()->paginate($perPage);

         $this->resetQueryRelate();

         return $paginate;
     }


    /**
     * @param $id
     * @return Model
     */
    protected function byId($id) : Model
    {
        //在QueryRelate 中的 __call中也没有找到first，需要用getQuery()，它指向的是Query\Builder
        $model = $this->queryRelate->where($this->getModel()->getKeyName(),$id)->getQuery()->first();

        $this->resetQueryRelate();

        if (empty($model)) {
            return $this->newModel();
        }

        return $model;
    }


    /**
     * @param int $id
     * @return Model
     */
    public function byIntId(int $id) : Model
    {
        return $this->byId($id);
    }

    /**
     * @param string $id
     * @return Model
     */
    public function byStringId(string $id) : Model
    {
        return $this->byId($id);
    }

    /**
     * @param string $field
     * @param string $value
     * @return mixed
     */
    public function oneByString(string $field, string $value) : Model
    {
        return $this->oneBy($field,$value);
    }

    /**
     * @param string $field
     * @param int $value
     * @return mixed
     */
    public function oneByInt(string $field, int $value) : Model
    {
        return $this->oneBy($field,$value);
    }

    /**
     * @param string $field
     * @param string $value
     * @return Model
     */
    public function oneByStringOrFail(string $field, string $value): Model
    {
        return $this->oneByOrFail($field,$value);
    }

    /**
     * @param string $field
     * @param int $value
     * @return Model
     */
    public function oneByIntOrFail(string $field, int $value): Model
    {
        return $this->oneByOrFail($field,$value);
    }


    /**
     * @param string $field
     * @param $value
     * @return mixed
     */
    public function oneBy(string $field, $value)
    {
        $model =  $this->queryRelate->where($field,$value)->getQuery()->first();

        $this->resetQueryRelate();

        if (empty($model)) {
            $model = $this->newModel();
        }

        return $model;
    }


    /**
     * @param string $field
     * @param $value
     * @return Model
     */
    public function oneByOrFail(string $field, $value): Model
    {
         $model = $this->oneBy($field,$value);
         if (empty($model)) {
             throw new ResourceNotFoundException();
         }
         return $model;
    }


    /**
     * @return Model
     */
    public function first() : Model
    {
         $model = $this->queryRelate->getQuery()->first();

         $this->resetQueryRelate();

         if (empty($model)) {
             return $this->newModel();
         }

         return $model;
    }


    /**
     * @return Model
     */
//    public function firstOrFail(): Model
//    {
//         $model = $this->first();
//         if (empty($model)) {
//             throw new ResourceNotFoundException();
//         }
//         return $model;
//    }


    /**
     * @return Collection
     */
    public function all(): Collection
    {
         //all表示不加任何条件，必须重新设置query
         $models = $this->queryRelate->setQuery($this->newQuery())->getQuery()->get();

         $this->resetQueryRelate();

         return $models;
    }


    /**
     * @return Collection
     */
    public function get(): Collection
    {
        $models = $this->queryRelate->getQuery()->get();

        $this->resetQueryRelate();

        return $models;
    }


    /**
     * @param string $column
     * @param string|null $key
     * @return Collection
     */
    public function pluck(string $column, string $key = null): Collection
    {
         $models = $this->queryRelate->getQuery()->pluck($column,$key);

         $this->resetQueryRelate();

         return $models;
    }


    /**
     * @param string $column
     * @return int
     */
    public function max(string $column): int
    {
         $max = $this->queryRelate->getQuery()->max($column);

         $this->resetQueryRelate();

         return $max;
    }


    /**
     * @param string $column
     * @return int
     */
    public function count(string $column = '*'): int
    {
         $count = $this->queryRelate->getQuery()->count($column);

         $this->resetQueryRelate();

         return $count;
    }


    /**
     * @param $column
     * @return int
     */
    public function avg($column): int
    {
         $avg = $this->queryRelate->getQuery()->avg($column);

         $this->resetQueryRelate();

         return $avg;
    }


    /**
     * @param string $column
     * @return int
     */
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


    /**
     * @param string $key
     * @param string $default
     * @return string
     */
    public function valueOfString(string $key, string $default = '') : string
    {
         return $this->value($key,$default);
    }


    /**
     * @param string $key
     * @param int $default
     * @return int
     */
    public function valueOfInt(string $key, int $default = 0) : int
    {
        return $this->value($key,$default);
    }


    /**
     * @param string $key
     * @param null $default
     * @return null
     */
    protected function value(string $key, $default)
    {
         $value = $this->queryRelate->getQuery()->value($key);

         $this->resetQueryRelate();

         if (empty($value)) {
             $value = $default;
         }

         return $value;
    }


    /**
     * @param string $column
     * @param int $amount
     * @param array $extra
     * @return int
     */
    public function increment(string $column, int $amount = 1, array $extra = []) : int
    {
         $rows = $this->queryRelate->getQuery()->increment($column,$amount,$extra);
         $this->resetQueryRelate();
         return $rows;
    }


    /**
     * @param string $column
     * @param int $amount
     * @param array $extra
     * @return int
     */
    public function decrement(string $column, int $amount = 1, array $extra = []) : int
    {
         $rows = $this->queryRelate->getQuery()->decrement($column,$amount,$extra);
         $this->resetQueryRelate();
         return $rows;
    }


    /**
     * @param string $event
     */
    protected function fireEvent(string $event,...$params)
    {
        Event::dispatch($event,$this,...$params);
    }


    /**
     *
     */
    protected function events()
    {
        //create or update events
    }


    /**
     *
     */
    protected function eventListen()
    {
        $this->events();
        Event::currentListenByArray($this->events);
    }


    /**
     * @param $name
     * @param $arguments
     * @return $this|mixed
     */
    public function __call($name, $arguments)
    {
        //queryRelate call

        //$forbidden = ['paginate','get','all','first','update','create','delete','find','value','pluck','chunk','sum','avg','count','max','min','increment','decrement'];
        //&& !in_array($name,$forbidden,true)
        if (method_exists($this->queryRelate,$name)) {
            $result = call_user_func_array([$this->queryRelate,$name],$arguments);
            if ($result instanceof $this->queryRelate) {
                $this->setQueryRelate($result);
                return $this;
            }
            return $result;
        }

        throw new MethodNotFoundException("method {{$name}} not found");
    }
}