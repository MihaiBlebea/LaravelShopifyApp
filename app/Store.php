<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;

class Store extends Model
{
    protected $fillable = ["app_id", "store_name", "store_email", "store_token"];

    public static function storeToken(Array $data)
    {
        if($data["store_token"] !== null)
        {
            self::create([
                "app_id"      => $data["app_id"],
                "store_name"  => $data["store_name"],
                "store_email" => $data["store_email"],
                "store_token" => $data["store_token"]
            ]);
        } else {
            throw new Exception("The Token can not be null", 1);
        }
    }

}
