<?php
namespace CrCms\Repository\Eloquent;

use CrCms\Repository\Contracts\Eloquent\Eloquent;
use CrCms\Repository\Contracts\Eloquent\QueryMagic;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Contracts\RepositoryQuery;
use CrCms\Repository\Exceptions\ResourceDeleteException;
use CrCms\Repository\Exceptions\ResourceNotFoundException;
use CrCms\Repository\Exceptions\ResourceStoreException;
use CrCms\Repository\Exceptions\ResourceUpdateException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Class AbstractRepository
 * @package CrCms\Repository\Repositories
 */
abstract class AbstractRepository implements Repository,RepositoryQuery,Eloquent
{
    /**
     * @var Builder
     */
    protected $query = null;

    /**
     * @var Model
     */
    protected $model = null;

    /**
     * @var array
     */
    protected $fillable = [];


    /**
     * @return Model
     */
    abstract public function newModel() : Model;


    /**
     * AbstractRepository constructor.
     */
    public function __construct()
    {
        $this->query = $this->newQuery();
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
     * @return Builder
     */
    public function getQuery()
    {
        return $this->query;
    }


     /**
      * @return Builder
      */
     protected function newQuery() : Builder
     {
         return $this->getModel()->newQuery();
     }


    /**
     * 兼容方法，下个版本删除
     * @return Builder
     */
     protected function getNewQuery() : Builder
     {
         return $this->newQuery();
     }


    /**
     * 兼容方法，下个版本删除
     * @return Builder
     */
    protected function getCurrentOrNewQuery() : Builder
    {
        $this->query ? : $this->query = $this->getNewQuery();
        return $this->query;
    }


     /**
      * @param array $fillable
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
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->get();
    }


    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->query->get();
    }


    /**
     * @param string $column
     * @param string $key
     * @return Collection
     */
    public function pluck(string $column, string $key = ''): Collection
    {
        return $this->query->pluck($column,$key);
    }


    /**
     * @param string $field
     * @param string $value
     * @return Collection
     */
    public function by(string $field, string $value): Collection
    {
        return $this->query->where($field,$value)->get();
    }


    /**
     * @param string $column
     * @return int
     */
    public function max(string $column): int
    {
        return $this->query->max($column);
    }


    /**
     * @param string $column
     * @return int
     */
    public function count(string $column): int
    {
        return $this->query->count($column);
    }


    /**
     * @param string $column
     * @return int
     */
    public function sum(string $column): int
    {
        return $this->query->sum($column);
    }


    /**
     * @param array $column
     * @return RepositoryQuery
     */
    public function column(array $column = ['*']): RepositoryQuery
    {
        $this->query->select($column);
        return $this;
    }


    /**
     * @param int $limit
     * @return RepositoryQuery
     */
    public function skip(int $limit): RepositoryQuery
    {
        $this->query->skip($limit);
        return $this;
    }


    /**
     * @param int $limit
     * @return RepositoryQuery
     */
    public function take(int $limit): RepositoryQuery
    {
        $this->query->take($limit);
        return $this;
    }


    /**
     * @param string $column
     * @return RepositoryQuery
     */
    public function groupBy(string $column): RepositoryQuery
    {
        $this->query->groupBy($column);
        return $this;
    }


    /**
     * @param string $column
     * @param string $sort
     * @return RepositoryQuery
     */
    public function orderBy(string $column, string $sort = 'desc'): RepositoryQuery
    {
        $this->query->orderBy($column,$sort);
        return $this;
    }


    /**
     * @param callable $callable
     * @return RepositoryQuery
     */
    public function callable(callable $callable): RepositoryQuery
    {
        $this->query = call_user_func($callable,$this->query);
        return $this;
    }


    /**
     * @param array $wheres
     * @return RepositoryQuery
     */
    public function wheres(array $wheres): RepositoryQuery
    {
        $this->query = (new ResolveWhereQuery)->getQuery($wheres,$this->query);
        return $this;
    }


    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query->paginate($perPage);
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
            throw new ResourceStoreException();
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
            throw new ResourceUpdateException();
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
            return $this->query->where('id',$id)->delete();
        } catch (\Exception $exception) {
            throw new ResourceDeleteException();
        }
    }


    /**
     * @param int $id
     * @return Model
     */
    public function byId(int $id): Model
    {
        try {
            return $this->query->where($this->getModel()->getKeyName(),$id)->findOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException();
        }
    }


    /**
     * @param string $field
     * @param string $value
     * @return Model
     */
    public function oneBy(string $field, string $value): Model
    {
        try {
            return $this->query->where($field,$value)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException();
        }
    }


    /**
     * @return Model
     */
    public function first(): Model
    {
        try {
            return $this->query->first();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException();
        }
    }


    /**
     * @param QueryMagic $queryMagic
     * @return Eloquent
     */
    public function magic(QueryMagic $queryMagic): Eloquent
    {
        $this->query = $queryMagic->magic($this->query,$this);
        return $this;
    }

}