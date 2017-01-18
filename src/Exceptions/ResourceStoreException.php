<?php
namespace CrCms\Repository\Exceptions;

class ResourceStoreException extends ResourceException
{
    public function __construct($message = "resource store fail")
    {
        parent::__construct($message);
    }
}