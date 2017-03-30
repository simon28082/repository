<?php
namespace CrCms\Repository\Concerns;
use CrCms\Repository\Facades\Event;

/**
 * Created by PhpStorm.
 * User: simon
 * Date: 17-3-25
 * Time: 上午11:15
 */
trait HasEvent
{

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

}