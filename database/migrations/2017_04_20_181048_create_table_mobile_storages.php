<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMobileStorages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_storages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('storage_id');
            $table->unsignedInteger('product_id');

            $table->foreign('storage_id')->references('id')->on('storages');
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
        Schema::dropIfExists('mobile_storages');
    }
}
