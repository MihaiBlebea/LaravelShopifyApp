<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Interfaces\AuthInterface;
use App\Models\App;
use App\Models\ShopifyApi;
use Exception;

class Store extends Model
{
    protected $fillable = [
        "store_name", "store_plan", "store_token", "store_owner", "store_domain", "store_email", "store_phone"
    ];

    // Define many-to-many relation
    public function apps()
    {
        return $this->belongsToMany('App\Models\App', 'app_store')->withTimestamps();
    }

    public function hasApp(App $app)
    {
        $result = DB::table("app_store")->where("app_id", $app->id)
                                        ->where("store_id", $this->id)
                                        ->first();
        return ($result !== null) ? true : false;
    }

    public static function storeNewToken(AuthInterface $api, Array $data)
    {
        // Get store details from Shopify api
        $details = $api->getApi()->Shop->get();

        // Store details in database with the token
        if($details)
        {
            $store = self::create([
                "store_token" => $data["store_token"],
                "store_domain" => $data["store_url"],
                "store_name" => $details["name"],
                "store_plan" => $details["plan_name"],
                "store_owner" => $details["shop_owner"],
                "store_email" => $details["email"],
                "store_phone" => $details["phone"]
            ]);

            return $store;
        } else {
            throw new Exception("Could not save the store " . $data["store_url"] . " in the database", 1);
        }
    }

    public function updatePivotTable(App $app)
    {
        $this->apps()->attach($app->id);
    }
}
