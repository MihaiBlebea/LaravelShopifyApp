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
}
