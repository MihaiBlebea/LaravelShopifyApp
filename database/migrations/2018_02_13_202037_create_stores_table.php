<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    public function up()
    {
        Schema::create("stores", function (Blueprint $table) {
            $table->increments("id");
            $table->string("store_name");
            $table->string("store_plan");
            $table->string("store_token");
            $table->string("store_owner");
            $table->string("store_domain");
            $table->string("store_email");
            $table->string("store_phone");
            $table->boolean("installed");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("stores");
    }
}
