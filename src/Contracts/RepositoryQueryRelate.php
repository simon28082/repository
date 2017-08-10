<?php

namespace CrCms\Repository\Contracts;

/**
 * Interface RepositoryQueryRelate
 *
 * @package CrCms\Repository\Contracts
 */
interface RepositoryQueryRelate
{
    /**
     * @param QueryRelate $queryRelate
     * @return mixed
     */
    public function setQueryRelate(QueryRelate $queryRelate);

    /**
     * @return QueryRelate
     */
    public function getQueryRelate(): QueryRelate;

    /**
     * @return void
     */
    public function resetQueryRelate();
}