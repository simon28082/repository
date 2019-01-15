<?php

namespace CrCms\Repository\Exceptions;

use BadMethodCallException;

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
    public function __construct(string $class, string $method)
    {
        $message = "Call to undefined method {$class}::{$method}";
        parent::__construct($message);
    }
}
