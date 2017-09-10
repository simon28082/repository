<?php

namespace CrCms\Repository\Exceptions;

class ResourceStoreException extends ResourceException
{
    /**
     * ResourceStoreException constructor.
     * @param string $message
     */
    public function __construct($message = "resource store fail")
    {
        parent::__construct($message);
    }
}