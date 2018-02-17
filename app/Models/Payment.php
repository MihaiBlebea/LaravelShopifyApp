<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\App;

class Payment extends Model
{
    protected $fillable = [
        "app_id", "payment_name", "payment_type", "payment_callback", "payment_price", "payment_trial"
    ];

    public function app()
    {
        return $this->belongsTo("App\Models\App");
    }
}
