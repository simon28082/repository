<?php
namespace CrCms\Repository\Drives;

use CrCms\Repository\AbstractRepository;
use CrCms\Repository\Contracts\QueryRelate;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Contracts\RepositoryQueryRelate;

abstract class RepositoryDriver  implements Repository,RepositoryQueryRelate
{

    protected $queryRelate = null;


    /**
     * @var AbstractRepository || null
     */
    protected $repository = null;

    /**
     * @return null
     */
    public function getRepository() : AbstractRepository
    {
        return $this->repository;
    }

    /**
     * @param null $repository
     */
    public function setRepository(AbstractRepository $repository) : self
    {
        $this->repository = $repository;
        dd($repository);
        return $this;
    }


    public function setQueryRelate(QueryRelate $queryRelate)
    {
        $this->queryRelate = $queryRelate;
        return $this;
    }

    public function getQueryRelate(): QueryRelate
    {
        return $this->queryRelate;
    }






}