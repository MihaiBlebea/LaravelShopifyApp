<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Store;
use App\Models\Asset;

class App extends Model
{
    protected $fillable = [
        "app_name", "app_slug", "app_key", "app_secret", "app_scopes", "is_active"
    ];

    public function getRouteKeyName()
    {
        return "app_slug";
    }

    public function stores()
    {
        return $this->belongsToMany('App\Models\Store', 'app_store')->withTimestamps();
    }

    public function assets()
    {
        return $this->hasMany('App\Models\Asset');
    }

    public function payment()
    {
        return $this->hasOne('App\Models\Payment');
    }
}
