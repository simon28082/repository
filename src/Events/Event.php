<?php
namespace CrCms\Repository\Events;

class Event
{

    protected static $listen = [];

    protected $currentListen = [];

    public function __construct()
    {
        //$this->globalListen = $this->listen = config('repository.listen');
    }


    public static function setListen(array $listen)
    {
        static::$listen = $listen;
    }

    public static function getListen() : array
    {
        static::$listen;
    }


    public function currentListenByArray(array $events)
    {
//        array_walk($events,function(&$event) {
//            $event = (array)$event;
//        });

        $events = array_map(function($event){
            return (array)$event;
        },$events);

        $this->currentListen = array_merge_recursive($this->currentListen,$events);


        return $this;
    }



    public function currentListenByString(string $event,string $value)
    {
        $this->currentListen[$event][] = $value;
        return $this;
    }


    protected function eventFilter(string $event,$repository) : array
    {
        $events = array_filter($this->listen[$event],function($listen,$key) use ($event,$repository){

            return (in_array($listen, $this->globalListen[$event], true)
            || $key === get_class($repository));

        },ARRAY_FILTER_USE_BOTH);

        $newEvents = [];
        array_map(function($listen) use (&$newEvents){
            $newEvents = array_merge($newEvents,(array)$listen);
        },$events);

        return $newEvents;
    }


    public function dispatch(string $event,$repository,...$params) : bool
    {
        $listenArray = array_merge(static::$listen[$event],$this->currentListen[$event]);
        return $this->dispatchHandle($event,$listenArray,$repository,...$params);
    }


    protected function dispatchHandle(string $event,array $listenArray,$repository,...$params) : bool
    {
        foreach ($listenArray as $listen) {

            if ($listen instanceof \Closure) {
                $result = $this->closureHandle($listen,$repository);
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

    protected function closureHandle(\Closure $listen,$repository)
    {
        return call_user_func($listen,$repository);
    }


    protected function methodHandle(string $listen,$repository,...$params)
    {
        list($class,$method) = explode('@',$listen);
        array_unshift($params,$repository);
        return call_user_func_array([new $class,$method],$params);
    }

    protected function classHandle(string $listen,string $event,$repository,...$params)
    {
        $class = new $listen($repository);
        if (method_exists($class,$event)) {
            $result =  $class->{$event}(...$params);
        } else {
            $result = $class->handle(...$params);
        }

        return $result;
    }


//    public static function instance()
//    {
//        if (!static::$instance instanceof static) {
//            static::$instance = new static;
//        }
//        return static::$instance;
//    }

}