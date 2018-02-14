<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppStore extends Migration
{
    public function up()
    {
        Schema::create("app_store", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("app_id");
            $table->integer("store_id");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("app_store");
    }
}
