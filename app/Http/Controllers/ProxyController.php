<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProxyHandler;
use File;
use Log;

class ProxyController extends Controller
{
    private $shop_name;

    private $prefix;

    private $app_name;

    private $folders = ["admin-pages", "sections", "assets", "snippets"];

    public function __construct(Request $request)
    {
        $this->shop_name = $request->query("shop");
        $this->prefix = $request->query("path_prefix");
        $proxy = new ProxyHandler();
        $this->app_name = $proxy->appName($this->prefix);
    }

    // Run path in browser to get this file https://mihaidev.myshopify.com/apps/payload/index
    public function index(Request $request)
    {
        $prefix = $request->query("path_prefix");
        $proxy = new ProxyHandler();
        $app_name = $proxy->appName($prefix);
        return $app_name;
    }

    // Call from browser or app
    // https://mihaidev.myshopify.com/apps/payload/get-file/assets/timer.js
    // shop_domain/proxy_prefix/sub_path/get-file/folder_name/file_name
    public function getFile($file_type, $file_name)
    {
        $path = public_path() . "/storage/files/" . $this->app_name . "/" . $file_type . "/" . $file_name;

        if(in_array($file_type, $this->folders) == false)
        {
            return response()->json([
                "code"     => 404,
                "response" => "The requested type/folder does not exist"
            ]);
        }

        if(File::exists($path) == false)
        {
            return response()->json([
                "code"     => 404,
                "response" => "The requested file was not found"
            ]);
        }

        Log::info("[Proxy]: File " . $path . " was requested by " . $this->shop_name);

        return file_get_contents($path);
    }

    public function storeSettings()
    {
        // TODO receive the settings with POST and save them in the database
    }

    public function getSettings()
    {
        // TODO get settings from database and send them back
    }
}
