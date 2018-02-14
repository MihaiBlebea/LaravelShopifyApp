<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;

class Store extends Model
{
    protected $fillable = [
        "store_name", "store_plan", "store_token", "store_owner", "store_domain", "store_email", "store_phone"
    ];

    public static function storeToken(Array $data)
    {
        if($data["store_token"] !== null)
        {
            self::create([
                "store_token" => $data["store_token"]
            ]);

            //TODO return store id
        } else {
            throw new Exception("The Token can not be null", 1);
        }
    }

}
