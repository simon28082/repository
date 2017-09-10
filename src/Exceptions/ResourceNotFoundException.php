<?php

namespace CrCms\Repository\Exceptions;

/**
 * Class ResourceNotFoundException
 *
 * @package CrCms\Repository\Exceptions
 */
class ResourceNotFoundException extends ResourceException
{
    /**
     * ResourceNotFoundException constructor.
     * @param string $message
     */
    public function __construct($message = "resource not found")
    {
        parent::__construct($message);
    }
}