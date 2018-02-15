<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create("payments", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("store_id");
            $table->integer("app_id");
            $table->double("price")->default(0.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("payments");
    }
}
