<?php

namespace CrCms\Repository\Exceptions;

use Throwable;

class ResourceNotFoundException extends ResourceException
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = 'Resources not found', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
