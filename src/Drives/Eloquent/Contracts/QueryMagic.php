<?php
namespace CrCms\Repository\Drives\Eloquent\Contracts;

use CrCms\Repository\Contracts\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Interface QueryMagic
 * @package CrCms\Repository\Contracts\Eloquent
 */
interface QueryMagic
{

    /**
     * @param Builder $query
     * @param Repository $repository
     * @return Builder
     */
    public function magic(Builder $query, Repository $repository) : Builder;

}