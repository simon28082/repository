<?php

namespace CrCms\Repository\Contract;

/**
 * Interface QueryExtendInterface
 * @package CrCms\Repository\Contract
 */
interface QueryMagicInterface
{

    /**
     * @param $query
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($query, RepositoryInterface $repository);

}