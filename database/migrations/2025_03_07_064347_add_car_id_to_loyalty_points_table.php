<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCarIdToLoyaltyPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loyalty_points', function (Blueprint $table) {
            $table->unsignedBigInteger('car_id')->nullable();
            $table->foreign('car_id')->references('car_id')->on('car_details')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loyalty_points', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->dropColumn('car_id');
        });
    }
}
