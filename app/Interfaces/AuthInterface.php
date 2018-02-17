<?php

namespace App\Interfaces;

interface AuthInterface
{
    public function __construct(Array $data);

    public function parseScopeString(String $scopes);

    public function addCallbackUrl(String $url = null);

    public function getApi();

    public function getCallbackUrl();

    public function retriveToken();
}
