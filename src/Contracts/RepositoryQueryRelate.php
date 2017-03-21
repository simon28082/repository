<?php
namespace CrCms\Repository\Contracts;

interface RepositoryQueryRelate
{


    public function setQueryRelate(QueryRelate $queryRelate);


    public function getQueryRelate() : QueryRelate;


    public function resetQueryRelate();

}