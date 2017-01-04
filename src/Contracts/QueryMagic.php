<?php

namespace CrCms\Repository\Contracts;

/**
 * Interface QueryExtendInterface
 * @package CrCms\Repository\Contract
 */
interface QueryMagic
{

    /**
     * @param $query
     * @param Repository $repository
     * @return mixed
     */
    public function apply($query, Repository $repository);

}