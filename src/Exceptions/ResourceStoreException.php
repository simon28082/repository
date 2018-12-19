<?php

namespace CrCms\Repository\Exceptions;

use Throwable;

class ResourceStoreException extends ResourceException
{
    /**
     * ResourceStoreException constructor.
     * @param string $message
     */
    public function __construct($message = "resource store fail", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}