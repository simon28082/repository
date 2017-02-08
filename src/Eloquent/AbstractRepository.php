<?php
namespace CrCms\Repository\Eloquent;

use CrCms\Repository\Contracts\Eloquent\Eloquent;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Exceptions\ResourceDeleteException;
use CrCms\Repository\Exceptions\ResourceNotFoundException;
use CrCms\Repository\Exceptions\ResourceStoreException;
use CrCms\Repository\Exceptions\ResourceUpdateException;
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



    public function __construct()
    {
        $this->queryRelate = $this->newQueryRelate($this->newQuery());
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
        return new QueryRelate($query,$this);
    }


    public function newDefaultQueryRelate() : QueryRelate
    {
        return $this->newQueryRelate($this->newQuery());
    }


    public function getQueryRelate() : QueryRelate
    {
        return $this->queryRelate;
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
     * @param int $id
     * @return Model
     */
    public function update(array $data, int $id): Model
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


    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        try {
            return $this->newQueryRelate($this->newQuery())->where('id',$id)->delete();
        } catch (\Exception $exception) {
            throw new ResourceDeleteException($exception->getMessage());
        }
    }


    /**
     * @param array $ids
     * @return int
     */
    public function deleteByArray(array $ids) : int
    {
        try {
            return $this->newQueryRelate($this->newQuery())->whereIn('id',$ids)->delete();
        } catch (\Exception $exception) {
            throw new ResourceDeleteException($exception->getMessage());
        }
    }

//    public function setQuery(Builder $query) : AbstractRepository
//    {
//        $this->query = $query;
//        return $this;
//    }


    public function paginate(int $perPage = 15): LengthAwarePaginator
     {
         return $this->queryRelate->orderBy($this->getModel()->getKeyName(),'desc')->getQuery()->paginate($perPage);
     }




    protected function byId($id)
    {
        return $this->newQueryRelate($this->newQuery())->where($this->getModel()->getKeyName(),$id)->getQuery()->first();
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
         return $this->newQueryRelate($this->newQuery())->where($field,$value)->getQuery()->first();
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
         $this->queryRelate->first();
     }

     public function firstOrFail(): Model
     {
         $model = $this->queryRelate->first();
         if (empty($model)) {
             throw new ResourceNotFoundException();
         }
     }

     public function all(): Collection
     {
         return $this->newQueryRelate($this->newQuery())->get();
     }

     public function get(): Collection
     {
        return $this->queryRelate->get();
     }

     public function pluck(string $column, string $key = ''): Collection
     {
         return $this->queryRelate->pluck($column,$key);
     }

     public function max(string $column): int
     {
         return $this->queryRelate->max($column);
     }

     public function count(string $column = '*'): int
     {
         return $this->queryRelate->count($column);
     }

     public function avg($column): int
     {
         return $this->queryRelate->avg($column);
     }

     public function sum(string $column): int
     {
         return $this->queryRelate->sum($column);
     }

     public function chunk(int $limit, callable $callback)
     {
         return $this->queryRelate->chunk($limit,$callback);
     }

     public function value(string $key)
     {
         return $this->queryRelate->value($key);
     }

     public function increment(string $column, int $amount = 1, array $extra = [])
     {
         return $this->queryRelate->increment($column,$amount,$extra);
     }

     public function decrement(string $column, int $amount = 1, array $extra = [])
     {
         return $this->queryRelate->decrement($column,$amount,$extra);
     }



     public function __call($name, $arguments)
    {
        if (method_exists($this->queryRelate,$name)) {
            $result = call_user_func_array([$this->queryRelate,$name],$arguments);
            if ($result instanceof $this->queryRelate) {
                $this->queryRelate = $result;
                return $this;
            }
            return $result;
        }
    }
}