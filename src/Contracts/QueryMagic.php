<?php
namespace CrCms\Repository\Contracts;

interface QueryMagic
{

    public function magic(QueryRelate $queryRelate, Repository $repository) : QueryRelate;

}