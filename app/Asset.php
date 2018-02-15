<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\App;

class Asset extends Model
{
    protected $fillable = [
        "app_id", "asset_name", "asset_type", "asset_path", "is_active"
    ];

    public function app()
    {
        return $this->belongsTo('App\App');
    }
}
