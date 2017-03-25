<?php
namespace CrCms\Repository\Concerns;
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 17-3-25
 * Time: 上午11:15
 */
trait HasData
{

    /**
     * @var array
     */
    protected $data = [];


    public function setData(array $data) : self
    {
        $this->data = $data;
        return $this;
    }


    public function getData() : array
    {
        return $this->data;
    }


}