<?php

namespace CrCms\Repository\Exceptions;

/**
 * Class ResourceDeleteException
 *
 * @package CrCms\Repository\Exceptions
 */
class ResourceDeleteException extends ResourceException
{
    /**
     * ResourceDeleteException constructor.
     * @param string $message
     */
    public function __construct($message = "resource delete fail")
    {
        parent::__construct($message);
    }
}