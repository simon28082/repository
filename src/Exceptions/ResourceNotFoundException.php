<?php

namespace CrCms\Repository\Exceptions;

use Throwable;

/**
 * Class ResourceNotFoundException.
 */
class ResourceNotFoundException extends ResourceException
{
    /**
     * ResourceNotFoundException constructor.
     *
     * @param string $message
     */
    public function __construct($message = 'Resource not found', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
