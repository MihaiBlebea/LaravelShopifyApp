<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $fillable = [
        "app_name", "app_slug", "app_key", "app_secret", "is_active"
    ];
}
