<?php

namespace CrCms\Repository\Contracts;

use CrCms\Repository\Drivers\Eloquent\QueryRelateContract;

interface QueryMagicContract
{
    /**
     * @param QueryRelateContract $queryRelate
     * @param RepositoryContract $repository
     *
     * @return QueryRelateContract
     */
    public function magic(QueryRelateContract $queryRelate, RepositoryContract $repository): QueryRelateContract;
}
