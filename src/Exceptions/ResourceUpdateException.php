<?php
namespace CrCms\Repository\Exceptions;

class ResourceUpdateException extends ResourceException
{
    public function __construct($message = "resource update fail")
    {
        parent::__construct($message);
    }
}