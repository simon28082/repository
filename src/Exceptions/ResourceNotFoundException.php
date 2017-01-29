<?php
namespace CrCms\Repository\Exceptions;

class ResourceNotFoundException extends ResourceException
{

    public function __construct($message = "resource not found")
    {
        parent::__construct($message);
    }

}