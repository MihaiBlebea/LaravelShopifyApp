<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\AuthInterface;
use App\Models\App;
use App\Models\ShopifyApi;

class Asset extends Model
{
    protected $fillable = [
        "app_id", "asset_name", "asset_type", "asset_path", "is_active"
    ];

    public function app()
    {
        return $this->belongsTo('App\Models\App');
    }

    public function install(AuthInterface $api)
    {
        $file_path = $this->assetPath();
        $theme_id = $this->getMainThemeId($api);

        if(in_array($this->asset_type, ["sections", "snippets", "assets"]) == true)
        {
            $asset = [
                "key" => $this->asset_type . "/" . $this->returnLiquid(),
                "value" => file_get_contents($file_path)
            ];
            return $api->getApi()->Theme($theme_id)->Asset->put($asset);
        } else {
            throw new Exception("Asset type not correct", 1);
        }
    }

    public function getMainThemeId(AuthInterface $api)
    {
        $themes = $api->getApi()->Theme->get();
        foreach($themes as $theme)
        {
            if($theme["role"] == "main")
            {
                return $theme["id"];
            }
        }
    }

    private function returnLiquid()
    {
        if($this->asset_type == "assets")
        {
            return $this->asset_name;
        } else {
            return (strpos($this->asset_name, ".liquid") == false) ? $this->asset_name . ".liquid" : $this->asset_name;
        }
    }

    public function assetPath()
    {
        return config('app.url') . (($this->asset_path[0] == "/") ? $this->asset_path : "/" . $this->asset_path);
    }
}
