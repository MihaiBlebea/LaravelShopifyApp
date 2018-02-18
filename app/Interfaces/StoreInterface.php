<?php

namespace App\Interfaces;

use App\Models\App;
use App\Interfaces\AuthInterface;

interface StoreInterface
{
    public function hasApp(App $app);

    public static function storeNewToken(AuthInterface $api, Array $data);

    public function updatePivotTable(App $app);
}
