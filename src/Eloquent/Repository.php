<?php

namespace CrCms\Repository\Repositories;


use CrCms\Repository\Contract\BaseModelInterface;
use CrCms\Repository\Contract\QueryExtendInterface;
use CrCms\Repository\Contract\RepositoryInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;

abstract class Repository implements RepositoryInterface,QueryExtendInterface
{

    /**
     * @var null
     */
    protected $query = null;

    /**
     * @var null
     */
    protected $model = null;

    /**
     * @var Container|null
     */
    protected $app = null;

    /**
     * Repository constructor.
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->model = $this->getModel();
    }

    /**
     * @return mixed
     */
    abstract public function getModel();


    /**
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return Collection
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): Collection
    {
        // TODO: Implement paginate() method.
    }

    /**
     * @param array $data
     * @return BaseModelInterface
     */
    public function create(array $data): BaseModelInterface
    {
        // TODO: Implement create() method.
    }

    /**
     * @param array $data
     * @param int $id
     * @return BaseModelInterface
     */
    public function update(array $data, int $id): BaseModelInterface
    {
        // TODO: Implement update() method.
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param int $id
     * @param array $columns
     * @return Collection
     */
    public function findById(int $id, array $columns = ['*']): Collection
    {
        // TODO: Implement findById() method.
    }

    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return Collection
     */
    public function findOneBy(string $field, string $value, array $columns = ['*']): Collection
    {
        // TODO: Implement findOneBy() method.
    }

    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @return Collection
     */
    public function findBy(string $field, string $value, array $columns = ['*']): Collection
    {
        // TODO: Implement findBy() method.
    }


    /**
     * @param $query
     * @param RepositoryInterface $repository
     */
    public function apply($query, RepositoryInterface $repository)
    {
        // TODO: Implement apply() method.
    }
}