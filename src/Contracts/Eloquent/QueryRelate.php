<?php
namespace CrCms\Repository\Contracts\Eloquent;

use Illuminate\Database\Query\Builder;

interface QueryRelate
{
    /**
     * @param string $query
     * @return QueryRelate
     */
    public function union(Builder $query) : QueryRelate;


    /**
     * @param QueryMagic $queryMagic
     * @return QueryRelate
     */
    public function magic(QueryMagic $queryMagic) : QueryRelate;

}