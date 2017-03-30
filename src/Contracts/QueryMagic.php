<?php
namespace CrCms\Repository\Contracts;

use CrCms\Repository\AbstractRepository;

interface QueryMagic
{

    public function magic(QueryRelate $queryRelate, AbstractRepository $repository) : QueryRelate;

}