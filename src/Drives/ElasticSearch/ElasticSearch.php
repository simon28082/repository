<?php
namespace CrCms\Repository\Drives\ElasticSearch;

use CrCms\Repository\AbstractRepository;
use CrCms\Repository\Contracts\Repository;
use CrCms\Repository\Drives\RepositoryDriver;
use CrCms\Repository\Drives\ElasticSearch\Contracts\ElasticSearch as ElasticSearchContract;
use Illuminate\Support\Collection;

/**
 * Class ElasticSearch
 *
 * @package CrCms\Repository\Drives\ElasticSearch
 */
class ElasticSearch extends RepositoryDriver implements ElasticSearchContract
{

    public function __construct(AbstractRepository $repository)
    {
        parent::__construct($repository);
    }

    public function newQueryRelate()
    {
        return new QueryRelate($this->newQuery(),$this);
    }

    public function newQuery()
    {

    }

    public function all(): Collection
    {
        // TODO: Implement all() method.
    }

    public function get(): Collection
    {
        // TODO: Implement get() method.
    }

    public function pluck(string $column, string $key = ''): Collection
    {
        // TODO: Implement pluck() method.
    }

    public function first()
    {
        // TODO: Implement first() method.
    }

    public function findByInt(int $id)
    {
        // TODO: Implement findByInt() method.
    }

    public function findByString(string $id)
    {
        // TODO: Implement findByString() method.
    }

    public function oneByInt(string $field, int $value)
    {
        // TODO: Implement oneByInt() method.
    }

    public function oneByString(string $field, string $value)
    {
        // TODO: Implement oneByString() method.
    }

    public function byStringId(string $id)
    {
        // TODO: Implement byStringId() method.
    }

    public function byIntId(int $id)
    {
        // TODO: Implement byIntId() method.
    }

    public function max(string $column): int
    {
        // TODO: Implement max() method.
    }

    public function count(string $column = '*'): int
    {
        // TODO: Implement count() method.
    }

    public function avg($column): int
    {
        // TODO: Implement avg() method.
    }

    public function sum(string $column): int
    {
        // TODO: Implement sum() method.
    }

    public function chunk(int $limit, callable $callback): bool
    {
        // TODO: Implement chunk() method.
    }

    public function valueOfString(string $key, string $default = ''): string
    {
        // TODO: Implement valueOfString() method.
    }

    public function valueOfInt(string $key, int $default = 0): int
    {
        // TODO: Implement valueOfInt() method.
    }

    public function increment(string $column, int $amount = 1, array $extra = []): int
    {
        // TODO: Implement increment() method.
    }

    public function decrement(string $column, int $amount = 1, array $extra = []): int
    {
        // TODO: Implement decrement() method.
    }

    public function update(array $data): int
    {
        // TODO: Implement update() method.
    }

    public function deleteByStringId(string $id): int
    {
        // TODO: Implement deleteByStringId() method.
    }

    public function deleteByIntId(int $id): int
    {
        // TODO: Implement deleteByIntId() method.
    }

    public function deleteByArray(array $ids): int
    {
        // TODO: Implement deleteByArray() method.
    }

    public function delete(): int
    {
        // TODO: Implement delete() method.
    }

    public function resetQueryRelate()
    {
        // TODO: Implement resetQueryRelate() method.
    }


}