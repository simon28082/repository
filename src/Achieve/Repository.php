<?php
namespace CrCms\Repository\Achieve;

use CrCms\Repository\Contracts\QueryRelate;
use CrCms\Repository\Contracts\Repository as ContractRepository;
use CrCms\Repository\Drives\Eloquent\Eloquent;
use CrCms\Repository\Drives\RepositoryDriver;
use Illuminate\Support\Collection;

class Repository implements ContractRepository
{

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var array
     */
    protected $data = [];


    protected $driver = null;

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







    public function __construct()
    {
        $this->driver = $this->driver();
    }


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



    public function setFillable(array $fillable) : ContractRepository
    {
        $this->fillable = $fillable;
        return $this;
    }


    /**
     * @param array $data
     * @return array
     */
    public function fillableFilter(array $data) : array
    {
        if (empty($this->fillable)) return $data;

        return array_filter($data,function($key) {
            return in_array($key,$this->fillable,true);
        },ARRAY_FILTER_USE_KEY);
    }




    public function driver() : RepositoryDriver
    {
        return new Eloquent();
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        //all表示不加任何条件，必须重新设置query
        $models = $this->driver->getQueryRelate()->setQuery($this->newQuery())->getQuery()->get();

        $this->resetQueryRelate();

        return $models;
    }


    /**
     * @return Collection
     */
    public function get(): Collection
    {
        $models = $this->driver->getQueryRelate()->getQuery()->get();

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

}