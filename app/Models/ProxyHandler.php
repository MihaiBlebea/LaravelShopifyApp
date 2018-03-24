<?php

namespace App\Models;

use App\Interfaces\ProxyInterface;

class ProxyHandler implements ProxyInterface
{
    public function __construct()
    {
        //
    }

    public function appName(String $path_prefix)
    {
        return explode("/", ltrim($path_prefix, "/"))[1];
    }

}
