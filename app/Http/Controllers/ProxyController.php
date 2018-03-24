<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;

class ProxyController extends Controller
{
    private $shop_name;

    private $folders = ["sections", "assets", "snippets"];

    public function __construct(Request $request)
    {
        $this->shop_name = $request->query("shop");
    }

    // Run path in browser to get this file https://mihaidev.myshopify.com/apps/payload/index
    public function index(Request $request)
    {
        return $this->shop_name;
    }

    // Call from browser or app
    // https://mihaidev.myshopify.com/apps/payload/get-file/assets/timer.js
    // shop_domain/proxy_prefix/sub_path/get-file/folder_name/file_name
    public function getFile(Request $request, $file_type, $file_name)
    {
        $path = base_path() . "/public/files/" . $file_type . "/" . $file_name;

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
