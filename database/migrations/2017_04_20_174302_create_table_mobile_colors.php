<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMobileColors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('color_id');
            $table->unsignedInteger('product_id');

            $table->foreign('color_id')->references('id')->on('colors');
            $table->foreign('product_id')->references('id')->on('product_data');
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
        Schema::dropIfExists('mobile_colors');
    }
}
