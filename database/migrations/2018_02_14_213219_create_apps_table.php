<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsTable extends Migration
{
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->increments('id');
            $table->string("app_name");
            $table->string("app_slug");
            $table->string("app_key");
            $table->string("app_secret");
            $table->boolean("is_active");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('apps');
    }
}
