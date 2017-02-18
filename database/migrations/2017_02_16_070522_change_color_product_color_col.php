<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColorProductColorCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('color_products', function (Blueprint $table) {
            $table->dropColumn('color');
            $table->unsignedInteger('color_id')->first();
            $table->foreign('color_id')->references('id')->on('colors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('color_product', function (Blueprint $table) {
            //
        });
    }
}
