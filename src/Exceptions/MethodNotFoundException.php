<?php
namespace CrCms\Repository\Exceptions;

class MethodNotFoundException extends \BadMethodCallException
{

    public function __construct(string $class,string $method)
    {
        $message = "Call to undefined method {$class}::{$method}";
        parent::__construct($message);
    }




}