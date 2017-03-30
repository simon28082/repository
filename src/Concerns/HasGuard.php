<?php
namespace CrCms\Repository\Concerns;
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 17-3-25
 * Time: 上午11:15
 */
trait HasGuard
{

    /**
     * @var array
     */
    protected $guard = [];

    public function setGuard(array $guard) : self
    {
        $this->guard = $guard;
        return $this;
    }


    public function getGuard() : array
    {
        return $this->guard;
    }


    /**
     * @param array $data
     * @return array
     */
    public function guard(array $data) : array
    {
        if (empty($this->guard)) {
            return [];
        }

        return array_filter($data,function($key) {
            return in_array($key,$this->guard,true);
        },ARRAY_FILTER_USE_KEY);
    }


}