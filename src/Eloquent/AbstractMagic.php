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
     * @var array
     */
    protected $guards = [];


    /**
     * @param array $data
     */
    public function setData(array $data) : self
    {
        $this->data = $data;
        return $this;
    }


    /**
     * @param array $prohibited
     * @return AbstractMagic
     */
    public function setGuards(array $guards) : self
    {
        $this->guards = $guards;
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
        return $this->dispatch($this->filter($data),$query);
    }


    /**
     * @param array $data
     * @return array
     */
    protected function filter(array $data) : array
    {
        return array_filter($data,function($item,$key){
            return !empty($item) && !in_array($key,$this->guards,true);
        },ARRAY_FILTER_USE_BOTH);
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
            $query = call_user_func_array([$this,$method],[$item,$query]);
        }

        return $query;
    }
}