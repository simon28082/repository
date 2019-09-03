<?php

namespace CrCms\Repository\Contracts;

interface Magic
{
    /**
     * @param $query
     * @param Any $repository
     *
     * @return Any
     */
    public function magic($query, Any $repository): Any;
}
