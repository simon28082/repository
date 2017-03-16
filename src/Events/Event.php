<?php
namespace CrCms\Repository\Events;

/**
 * Class Event
 * @package CrCms\Repository\Events
 */
class Event
{

    /**
     * @var array
     */
    protected static $listen = [];

    /**
     * @var array
     */
    protected $currentListen = [];


    /**
     * @param array $listen
     */
    public static function setListen(array $listen)
    {
        static::$listen = $listen;
    }


    /**
     * @return array
     */
    public static function getListen() : array
    {
        static::$listen;
    }


    /**
     * @param array $events
     * @return $this
     */
    public function currentListenByArray(array $events)
    {
        $events = array_map(function($event){
            return (array)$event;
        },$events);

        $this->currentListen = array_merge_recursive($this->currentListen,$events);

        return $this;
    }


    /**
     * @param string $event
     * @param string $value
     * @return $this
     */
    public function currentListenByString(string $event,string $value)
    {
        $this->currentListen[$event][] = $value;
        return $this;
    }


//    protected function eventFilter(string $event,$repository) : array
//    {
//        $events = array_filter($this->listen[$event],function($listen,$key) use ($event,$repository){
//
//            return (in_array($listen, $this->globalListen[$event], true)
//            || $key === get_class($repository));
//
//        },ARRAY_FILTER_USE_BOTH);
//
//        $newEvents = [];
//        array_map(function($listen) use (&$newEvents){
//            $newEvents = array_merge($newEvents,(array)$listen);
//        },$events);
//
//        return $newEvents;
//    }


    /**
     * @param string $event
     * @param $repository
     * @param array ...$params
     * @return bool
     */
    public function dispatch(string $event,$repository,...$params) : bool
    {
        /*$listenArray = $this->collapse(
            array_merge((array)static::$listen[$event],$this->currentListen[$event])
        );*/

        $listenArray = array_merge((array)static::$listen[$event],$this->currentListen[$event]);

        return $this->dispatchHandle($event,$listenArray,$repository,...$params);
    }


    /**
     * @param array $listenArray
     * @return array
     */
    protected function collapse(array $listenArray) : array
    {
        $newListenArray = [];

        foreach ($listenArray as $listen) {
            $newListenArray = array_merge($newListenArray,$listen);
        }

        return $newListenArray;
    }


    /**
     * @param string $event
     * @param array $listenArray
     * @param $repository
     * @param array ...$params
     * @return bool
     */
    protected function dispatchHandle(string $event,array $listenArray,$repository,...$params) : bool
    {
        foreach ($listenArray as $listen) {

            if ($listen instanceof \Closure) {
                $result = $this->closureHandle($listen,$repository,...$params);
            } elseif (strpos($listen,'@') !== false) {
                $result = $this->methodHandle($listen,$repository,...$params);
            } else {
                $result = $this->classHandle($listen,$event,$repository,...$params);
            }

            //false则中断执行
            if ($result === false) {
                return false;
            }
        }

        return true;
    }


    /**
     * @param \Closure $listen
     * @param $repository
     * @return mixed
     */
    protected function closureHandle(\Closure $listen,$repository)
    {
        return call_user_func($listen,$repository);
    }


    /**
     * @param string $listen
     * @param $repository
     * @param array ...$params
     * @return mixed
     */
    protected function methodHandle(string $listen,$repository,...$params)
    {
        list($class,$method) = explode('@',$listen);
        array_unshift($params,$repository);
        return call_user_func_array([new $class,$method],$params);
    }


    /**
     * @param string $listen
     * @param string $event
     * @param $repository
     * @param array ...$params
     * @return mixed
     */
    protected function classHandle(string $listen,string $event,$repository,...$params)
    {
        $class = new $listen($repository);
        if (method_exists($class,$event)) {
            $result =  $class->{$event}($repository,...$params);
        } else {
            $result = $class->handle($repository,...$params);
        }

        return $result;
    }

}