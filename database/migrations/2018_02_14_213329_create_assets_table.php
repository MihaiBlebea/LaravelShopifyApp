<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    public function up()
    {
        Schema::create("assets", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("app_id");
            $table->string("asset_name");
            $table->string("asset_type");
            $table->string("asset_path");
            $table->boolean("is_active")->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("assets");
    }
}
