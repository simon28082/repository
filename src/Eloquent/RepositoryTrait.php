<?php

namespace CrCms\Repository\Repositories;


use CrCms\Repository\Contract\QueryMagicInterface;
use CrCms\Repository\Contract\RepositoryInterface;
use CrCms\Repository\Contract\RepositoryMagicInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;

trait RepositoryTrait
{

    /**
     * @var null
     */
    protected $query = null;

    /**
     * @var null
     */
    protected $model = null;


    /**
     * @return mixed
     */
    abstract public function getModel();


    /**
     * @return mixed
     */
    abstract public function getNewQuery();


    /**
     * @param QueryMagicInterface $queryMagic
     * @return $this
     */
    public function findByQueryMagic(RepositoryMagicInterface $queryMagic) : RepositoryInterface
    {
        $this->query = $queryMagic->apply($this->query,$this);
        return $this;
    }


    /**
     * @param callable $callable
     * @return RepositoryInterface
     */
    public function findByQueryCallback(callable $callable) : RepositoryInterface
    {
        $this->query = call_user_func($callable,$this->query);
        return $this;
    }


    /**
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->query->select($columns)->orderBy($this->model->getKeyName(),'desc')->get();
    }


    /**
     * @param int $perPage
     * @param array $columns
     * @return Collection
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): Collection
    {
        return $this->query->select($columns)->orderBy($this->model->getKeyName(),'desc')->paginate($perPage);
    }


    /**
     * @param array $data
     * @return Collection
     */
    public function create(array $data): Collection
    {
        $model = $this->model->create($data);
        return collect($model);
    }


    /**
     * @param array $data
     * @param int $id
     * @return Collection
     */
    public function update(array $data, int $id): Collection
    {
        $model = $this->findById($id);

        foreach ($data as $key=>$value)
        {
            $model->{$key} = $value;
        }

        $model->save();

        return collect($model);
    }


    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        return $this->query->where('id',$id)->delete();
    }


    /**
     * @param int $id
     * @param array $columns
     * @return Collection
     */
    public function findById(int $id, array $columns = ['*']): Collection
    {
        return $this->query->select($columns)->where($this->model->getKeyName(),$id)->firstOrFail();
    }


    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return Collection
     */
    public function findOneBy(string $field, string $value, array $columns = ['*']): Collection
    {
        return $this->query->select($columns)->where($field,$value)->orderBy($this->model->getKeyName(),'desc')->first();
    }


    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return Collection
     */
    public function findBy(string $field, string $value, array $columns = ['*']): Collection
    {
        return $this->query->select($columns)->where($field,$value)->orderBy($this->model->getKeyName(),'desc')->get();
    }

}