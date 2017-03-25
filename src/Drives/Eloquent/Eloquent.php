<?php
namespace CrCms\Repository\Drives\Eloquent;

use CrCms\Repository\AbstractRepository;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Contracts\RepositoryQueryRelate;
use CrCms\Repository\Drives\Eloquent\Contracts\Eloquent as EloquentRepository;
use CrCms\Repository\Contracts\QueryRelate;
use CrCms\Repository\Drives\RepositoryDriver;
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
class Eloquent extends RepositoryDriver
{

    /**
     * AbstractRepository constructor.
     */
    public function __construct()
    {
        $this->setQueryRelate($this->newQueryRelate($this->newQuery()));
    }

    /**
     * @return AbstractRepository
     */
    public function resetQueryRelate()
    {
        return $this->setQueryRelate($this->queryRelate->setQuery($this->newQuery()));
    }

    protected function byIdOrFail($id)
    {
        $model = $this->byId($id);
        if (empty($model)) {
            throw new ResourceNotFoundException();
        }
        return $model;
    }
    public function byIntIdOrFail(int $id): Model
    {
        return $this->byIdOrFail($id);
    }
    public function byStringIdOrFail(string $id): Model
    {
        return $this->byIdOrFail($id);
    }


    /**
     * @return Builder
     */
    public function newQuery() : Builder
    {
        return $this->repository->getModel()->newQuery();
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
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        try {
            $model =  $this->repository->newModel()->create($data);
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
    public function update(array $data, $id): Model
    {
        $model = $this->byId($id);

        array_walk($data,function ($value,$key) use ($model){
            $model->{$key} = $value;
        });

        try {
            $model->save();
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
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
     {
         $paginate = $this->queryRelate->orderBy($this->model->getKeyName(),'desc')->getQuery()->paginate($perPage);

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
        $model = $this->queryRelate->where($this->model->getKeyName(),$id)->getQuery()->first();

        $this->resetQueryRelate();

        if (empty($model)) {
            return $this->repository->newModel();
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
            $model = $this->repository->newModel();
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
             return $this->repository->newModel();
         }

         return $model;
    }


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
     * @param int $id
     * @return int
     */
    public function delete($id): int
    {
        $rows = 0;
        try {
            $rows = $this->queryRelate->whereIn('id',(array)$id)->getQuery()->delete();
        } catch (\Exception $exception) {
            throw new ResourceDeleteException($exception->getMessage());
        } finally {
            $this->resetQueryRelate();
        }

        return $rows;
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
     * @return Model
     */
    public function firstOrFail(): Model
    {
         $model = $this->first();
         if (empty($model)) {
             throw new ResourceNotFoundException();
         }
         return $model;
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

        throw new MethodNotFoundException(static::class,$name);
    }
}