<?php

namespace App\Interfaces;

use App\Models\App;
use App\Interfaces\AuthInterface;

interface StoreInterface
{
    public function hasApp(App $app);

    public function updateAppStoreTable(App $app);
}
