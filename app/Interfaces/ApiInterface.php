<?php

namespace App\Interfaces;

use App\Models\Store;

interface ApiInterface
{
    public function __construct(Store $store);

    public function getApi();
}
