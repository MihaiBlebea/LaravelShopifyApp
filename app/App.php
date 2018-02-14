<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Store;

class App extends Model
{
    protected $fillable = [
        "app_name", "app_slug", "app_key", "app_secret", "is_active"
    ];

    public function stores()
    {
        return $this->belongsToMany('App\Store', 'app_store');
    }
}
