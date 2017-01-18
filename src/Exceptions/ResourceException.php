<?php
namespace CrCms\Repository\Exceptions;

use RuntimeException;
class ResourceException extends RuntimeException
{

    public function __construct($message = "")
    {
        parent::__construct($message);
    }

}