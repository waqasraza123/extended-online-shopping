<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrandIdToMobilesLaptops extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mobiles', function(Blueprint $table){
            if(!Schema::hasColumn('mobiles', 'brand_id')){
                $table->unsignedInteger('brand_id');
                $table->foreign('brand_id')->references('id')->on('brands');
            }
            if(!Schema::hasColumn('mobiles', 'brand')){
                $table->dropColumn('brand');
            }
        });

        Schema::table('laptops', function(Blueprint $table){
            if(!Schema::hasColumn('mobiles', 'brand_id')){
                $table->unsignedInteger('brand_id');
                $table->foreign('brand_id')->references('id')->on('brands');
            }
            if(!Schema::hasColumn('mobiles', 'brand')){
                $table->dropColumn('brand');
            }
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
