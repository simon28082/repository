<?php
namespace CrCms\Repository\Facades;

use Illuminate\Support\Facades\Facade;

class Event extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'repository.event';
    }

}