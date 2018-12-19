<?php

namespace CrCms\Repository\Exceptions;

use Throwable;

/**
 * Class ResourceUpdateException
 *
 * @package CrCms\Repository\Exceptions
 */
class ResourceUpdateException extends ResourceException
{
    /**
     * ResourceUpdateException constructor.
     * @param string $message
     */
    public function __construct($message = "resource update fail", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}