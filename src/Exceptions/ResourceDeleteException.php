<?php
namespace CrCms\Repository\Exceptions;

class ResourceDeleteException extends ResourceException
{

    public function __construct($message = "resource delete fail")
    {
        parent::__construct($message);
    }

}