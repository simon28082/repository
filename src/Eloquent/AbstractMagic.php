<?php
namespace CrCms\Repository\Eloquent;

use CrCms\Repository\Contracts\Eloquent\QueryMagic;
use Illuminate\Database\Eloquent\Builder;
use CrCms\Repository\Contracts\Repository;

/**
 * Class AbstractMagic
 * @package CrCms\Repository\Eloquent
 */
abstract class AbstractMagic implements QueryMagic
{
    /**
     * @var array
     */
    protected $data = [];


    /**
     * @param array $data
     */
    public function setData(array $data) : self
    {
        $this->data = $data;
        return $this;
    }


    /**
     * @param Builder $query
     * @param Repository $repository
     * @return Builder
     */
    public function magic(Builder $query, Repository $repository) : Builder
    {
        return $this->magicSearch($this->data,$query);
    }


    /**
     * @param array $data
     * @param Builder $query
     * @return Builder
     */
    protected function magicSearch(array $data, Builder $query) : Builder
    {
        return $this->dispatch($data,$query);
    }


    /**
     * @param array $data
     * @param Builder $query
     * @return Builder
     */
    protected function dispatch(array $data, Builder $query) : Builder
    {
        foreach ($data as $key=>$item) {
            $method = 'by'.studly_case($key);

            if (method_exists($this,$method)) {
                $query = call_user_func_array([$this,$method],[$item,$query]);
            }
        }
        return $query;
    }
}