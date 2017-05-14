<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('mobiles', function (Blueprint $table) {
            $table->dropColumn(['link', 'old_price', 'current_price', 'discount', 'shop_id', 'local_online', 'stock']);
            $table->dropForeign('mobiles_shop_id_foreign');
            $table->dropColumn('shop_id');
            if(!Schema::hasColumn('mobiles', 'storage'))
                $table->unsignedInteger('storage');
            if(!Schema::hasColumn('mobiles', 'color'))
                $table->string('color');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mobiles', function (Blueprint $table) {
            //
        });
    }
}
