<?php

namespace CrCms\Repository\Drivers;

use CrCms\Repository\AbstractRepository;
use CrCms\Repository\Contracts\QueryRelate;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Contracts\RepositoryQueryRelate;

/**
 * Class RepositoryDriver.
 */
abstract class RepositoryDriver implements Repository, RepositoryQueryRelate
{
    /**
     * @var QueryRelate
     */
    protected $queryRelate;

    /**
     * @var AbstractRepository
     */
    protected $repository;

    /**
     * RepositoryDriver constructor.
     *
     * @param AbstractRepository $repository
     */
    public function __construct(AbstractRepository $repository)
    {
        $this->setRepository($repository);
    }

    /**
     * @return AbstractRepository
     */
    public function getRepository(): AbstractRepository
    {
        return $this->repository;
    }

    /**
     * @param AbstractRepository $repository
     *
     * @return RepositoryDriver
     */
    public function setRepository(AbstractRepository $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @param QueryRelate $queryRelate
     *
     * @return $this
     */
    public function setQueryRelate(QueryRelate $queryRelate)
    {
        $this->queryRelate = $queryRelate;

        return $this;
    }

    /**
     * @return QueryRelate
     */
    public function getQueryRelate(): QueryRelate
    {
        return $this->queryRelate;
    }
}
