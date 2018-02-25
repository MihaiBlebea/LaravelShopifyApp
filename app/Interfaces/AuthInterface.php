<?php

namespace App\Interfaces;

interface AuthInterface
{
    public function __construct(Array $data);

    public function getCallbackUrl();

    public function retriveToken();
}
