<?php

namespace App\Interfaces;

use App\Interfaces\ApiInterface;
use App\Models\App;

interface InstallInterface
{
    public function __construct(ApiInterface $api);

    public function installApp(App $app);

    public function getAsset(String $theme_id, String $key);

    public function uninstallApp(App $app);
}
