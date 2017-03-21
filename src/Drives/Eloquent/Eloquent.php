<?php
namespace CrCms\Repository\Drives\Eloquent;

use CrCms\Repository\Contracts\RepositoryQueryRelate;
use CrCms\Repository\Drives\Eloquent\Contracts\Eloquent as EloquentRepository;
use CrCms\Repository\Contracts\QueryRelate;
use CrCms\Repository\Contracts\Repository;
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
class Eloquent extends RepositoryDriver implements EloquentRepository,RepositoryQueryRelate
{

    /**
     * @var Model
     */
    protected $model = null;

    /**
     * @var array
     */
    protected $fillable = [];

    protected $queryRelate = null;



    public function setQueryRelate(QueryRelate $queryRelate)
    {
        $this->queryRelate = $queryRelate;
        return $this;
    }

    public function getQueryRelate(): QueryRelate
    {
        return $this->queryRelate;
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
     * @return AbstractRepository
     */
    public function resetQueryRelate()
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