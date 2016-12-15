<?php
namespace CrCms\Repository\Repositories\Traits;

use Illuminate\Database\Eloquent\Model;

trait RepositoryTrait
{

    /**
     * @var $model Illuminate\Database\Eloquent\Model
     */
    protected $model = null;


    /**
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = ['*'])
    {
        return $this->model->select($columns)->orderBy($this->model->getKeyName(),'desc')->get();
    }


    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function findAllPaginate(int $perPage = 15, array $columns = ['*'])
    {
        return $this->model->select($columns)->orderBy($this->model->getKeyName(),'desc')->paginate($perPage);
    }


    /**
     * @param array $data
     * @return static
     */
    public function create(array $data) : Model
    {
        return $this->model->create($data);
    }


    /**
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data,int $id) : Model
    {
        $model = $this->findById($id);

        foreach ($data as $key=>$value)
        {
            $model->{$key} = $value;
        }

        $model->save();

        return $model;
    }


    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id) : int
    {
        return $this->model->destroy($id);
    }


    /**
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function findById(int $id, array $columns = ['*'])
    {
        return $this->model->select($columns)->where($this->model->getKeyName(),$id)->firstOrFail();
    }


    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return mixed
     */
    public function findOneBy(string $field,string $value,array $columns = ['*'])
    {
        return $this->model->select($columns)->where($field,$value)->orderBy($this->model->getKeyName(),'desc')->first();
    }


    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return mixed
     */
    public function findBy(string $field,string $value,array $columns = ['*'])
    {
        return $this->model->select($columns)->where($field,$value)->orderBy($this->model->getKeyName(),'desc')->get();
    }
}