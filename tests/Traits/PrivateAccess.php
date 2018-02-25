<?php

namespace Tests\Traits;

use ReflectionClass;

trait PrivateAccess
{
    protected static function getMethod(String $class, String $name)
    {
        $class = new ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
