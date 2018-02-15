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
            $table->string("store_name")->nullable();
            $table->string("store_plan")->nullable();
            $table->string("store_token")->nullable();
            $table->string("store_owner")->nullable();
            $table->string("store_domain")->nullable();
            $table->string("store_email")->nullable();
            $table->string("store_phone")->nullable();
            $table->boolean("installed")->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("stores");
    }
}
