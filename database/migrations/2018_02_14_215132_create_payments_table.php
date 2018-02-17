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
            $table->integer("app_id");
            $table->string("payment_name");
            $table->string("payment_type");
            $table->string("payment_callback");
            $table->double("payment_price")->default(0);
            $table->integer("payment_trial")->default(NULL);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("payments");
    }
}
