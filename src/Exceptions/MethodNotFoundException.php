<?php
namespace CrCms\Repository\Exceptions;

class MethodNotFoundException extends \BadMethodCallException
{

    public function __construct($message = "method not found")
    {
        parent::__construct($message);
    }

}