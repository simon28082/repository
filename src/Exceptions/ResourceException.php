<?php

namespace CrCms\Repository\Exceptions;

use RuntimeException;

class ResourceException extends RuntimeException
{
    /**
     * ResourceException constructor.
     * @param string $message
     */
    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}