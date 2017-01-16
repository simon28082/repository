<?php
namespace CrCms\Repository\Eloquent;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class ResolveWhereQuery
 * @package CrCms\Repository\Eloquent
 */
class ResolveWhereQuery
{

    /**

     * [
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
    ]
     *
     * @param array $wheres
     * @param Builder $query
     * @return Builder
     */
    protected function handle(array $wheres, Builder $query) : Builder
    {
        foreach ($wheres as $where) {

            $method = array_shift($where);

            //子集解析
            if (is_array($where[0])) {
                $query = call_user_func([$query,$method],function($inQuery) use ($where){
                    $this->handle($where,$inQuery);
                });
            } else {
                $query = call_user_func_array([$query,$method],$where);
            }
        }

        return $query;
    }


    /**
     * @return Builder
     */
    public function getQuery(array $wheres, Builder $query) : Builder
    {
        return $this->handle($this->resolve($wheres),$query);
    }


    /**
     * 简写方法解析
     * @param array $wheres
     * @return array
     */
    protected function resolve(array $wheres) : array
    {
        $whereRecursiveCount = count($wheres,COUNT_RECURSIVE);

        //['id',1] => [['where','id',=,1]]
        if ($whereRecursiveCount === 2) {
            return ['where',[$wheres[0],'=',$wheres[1]]];
        }

        //['id',>,1] => [['where','id',>,1]]
        if ($whereRecursiveCount === 3) {
            return [array_unshift($wheres,'where')];
        }

        return $wheres;
    }
}