<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShopForeignKeyToMobilesLaptops extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mobiles', function (Blueprint $t){
            $t->foreign('shop_id')->references('id')->on('shops');
        });
        Schema::table('laptops', function (Blueprint $t){
            $t->foreign('shop_id')->references('id')->on('shops');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::enableForeignKeyConstraints();
        Schema::table('laptops', function (Blueprint $t){
            $table->dropForeign(['shop_id']);
        });
        Schema::table('mobiles', function (Blueprint $t){
            $table->dropForeign(['shop_id']);
        });
        Schema::disableForeignKeyConstraints();
    }
}
