<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    public function install(String $type, ShopifyApi $api)
    {
        $file_path = $this->assetPath();
        $theme_id = $this->getMainThemeId($api);

        if(in_array($type, ["sections", "snippets", "assets"]) == true)
        {
            $asset = [
                "key" => $type . "/" . $this->returnLiquid($type, $this->asset_name),
                "value" => file_get_contents($file_path)
            ];
            return $api->getApi()->Theme($theme_id)->Asset->put($asset);
        } else {
            throw new Exception("Asset type not correct", 1);
        }
    }

    public function getMainThemeId(ShopifyApi $api)
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

    private function returnLiquid(String $type, String $file)
    {
        if($type == "assets")
        {
            return $file;
        } else {
            return (strpos($file, ".liquid") == false) ? $file . ".liquid" : $file;
        }
    }

    public function assetPath()
    {
        return config('app.url') . (($this->asset_path[0] == "/") ? $this->asset_path : "/" . $this->asset_path);
    }
}
