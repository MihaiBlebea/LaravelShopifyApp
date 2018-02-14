<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        "app_id", "asset_name", "asset_type", "asset_path", "is_active"
    ];
}
