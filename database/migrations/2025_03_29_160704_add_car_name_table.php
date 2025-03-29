<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCarNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_loyalty_earnings', function (Blueprint $table) {
            $table->string('car_name')->after('booking_id')->nullable();
            $table->string('discount')->after('car_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_loyalty_earnings', function (Blueprint $table) {
            $table->dropColumn('car_name');
            $table->dropColumn('discount');
        });
    }
}
