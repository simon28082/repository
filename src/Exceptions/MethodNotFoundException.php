<?php

namespace CrCms\Repository\Exceptions;

use BadMethodCallException;
use Throwable;

/**
 * Class MethodNotFoundException.
 */
class MethodNotFoundException extends BadMethodCallException
{
    /**
     * MethodNotFoundException constructor.
     *
     * @param string $class
     * @param string $method
     */
    public function __construct(string $class, string $method, Throwable $previous = null)
    {
        $message = "Call to undefined method {$class}::{$method}";
        parent::__construct($message, 0, $previous);
    }
}
