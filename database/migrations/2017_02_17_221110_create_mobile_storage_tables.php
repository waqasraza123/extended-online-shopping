<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileStorageTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storages', function (Blueprint $t){
            $t->increments('id');
            $t->unsignedInteger('storage');
        });

        //create middle table
        Schema::create('mobile_storage', function (Blueprint $t){
            $t->increments('id');
            $t->unsignedInteger('storage_id');
            $t->unsignedInteger('mobile_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
