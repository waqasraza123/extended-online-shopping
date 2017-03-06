<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_data', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id');
            $table->unsignedInteger('mobile_id');
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->foreign('mobile_id')->references('id')->on('mobiles');
            $table->string('image');
            $table->string('link');
            $table->string('old_price');
            $table->string('current_price');
            $table->string('discount');
            $table->string('local_online');//l for local and o for online
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_data');
    }
}
