<?php

namespace CrCms\Repository\Exceptions;

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
    public function __construct($message = "resource update fail")
    {
        parent::__construct($message);
    }
}