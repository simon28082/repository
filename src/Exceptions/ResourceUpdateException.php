<?php

namespace CrCms\Repository\Exceptions;

use Throwable;

class ResourceUpdateException extends ResourceException
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = 'Resource update failed', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
