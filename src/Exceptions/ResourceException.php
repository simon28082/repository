<?php

namespace CrCms\Repository\Exceptions;

use RuntimeException;
use Throwable;

class ResourceException extends RuntimeException
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
