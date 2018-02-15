<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\App;
use App\Store;

class Payment extends Model
{
    protected $fillable = [
        "store_id", "app_id", "price"
    ];

    public function app()
    {
        return $this->belongsTo("App/App");
    }

    public function store()
    {
        return $this->belongsTo("App/Store");
    }
}
