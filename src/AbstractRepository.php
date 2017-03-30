<?php
namespace CrCms\Repository;

use CrCms\Repository\Concerns\HasData;
use CrCms\Repository\Concerns\HasEvent;
use CrCms\Repository\Concerns\HasGuard;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Drives\Eloquent\Eloquent;
use CrCms\Repository\Exceptions\MethodNotFoundException;

abstract class AbstractRepository
{


    use HasData,HasEvent,HasGuard;




    protected $driver = null;



    protected $model = null;






    public function __construct()
    {
        $this->driver = $this->driver();
    }


    public function getModel()
    {
        if (!$this->model) {
            $this->model = $this->newModel();
        }
        return $this->model;
    }




    abstract public function newModel();



    public function create(array $data)
    {
        $this->setData($this->guard($data));

        //这里是中断，要不要返回一个空模型，要思考
        if ($this->fireEvent('creating') === false) {
            return $this->newModel();
        }

        $model = $this->driver->create($this->data);

        $this->fireEvent('created',$model);

        return $model;
    }


    protected function update(array $data, $id)
    {
        $this->setData($this->guard($data));

        if ($this->fireEvent('updating') === false) {
            return $this->getModel();
        }

        $model = $this->driver->update($data,$id);

        $this->fireEvent('updated',$model);

        return $model;
    }


    /**
     * @param int $id
     * @return int
     */
    protected function delete($id)
    {
        $this->setData((array)$id);

        if ($this->fireEvent('deleting') === false) {
            return 0;
        }

        $rows = $this->driver->delete($this->data);

        $this->fireEvent('deleted');

        return $rows;
    }


    public function driver() : Repository
    {
        return (new Eloquent($this));
    }



    public function __call($name, $arguments)
    {
//        if (method_exists($this->driver,$name)) {
            return $this->driver->$name(...$arguments);
//        }

        //throw new MethodNotFoundException(static::class,$name);
    }


}