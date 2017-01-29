<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RepositoryTest extends TestCase
{

    protected $repository = null;


    public function testByWhere()
    {
        $this->repository = new \App\Repositories\AlarmRepository();
        \Illuminate\Support\Facades\DB::enableQueryLog();
        $this->repository->byWhere([
            ['orWhere','id','=',1],
            ['where','id','=',2],
            ['orWhere','id',3],
                [
                    'where',
                    ['where','id','=',5],
                    ['orWhere',
                        ['whereBetween','create_time',[1000,2000]],
                        ['whereNotIn','id',[5,6]]
                    ],
                ]
        ]);
    }


    public function testGuard()
    {
        $data = ['a'=>1,'b'=>2,'c'=>3];
        $guards = ['a','c'];
        $keys = array_keys($data);
        $keys = array_intersect($guards,$keys);

        $array = array_filter($data,function($key) use ($guards) {
           return in_array($key,$guards,true);
        },ARRAY_FILTER_USE_KEY);
    }
}
