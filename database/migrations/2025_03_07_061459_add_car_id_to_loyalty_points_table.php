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
            $table->unsignedBigInteger('car_id')->after('id')->nullable(); // Add car_id
            $table->foreign('car_id')->references('id')->on('car_details')->onDelete('cascade')->onUpdate('cascade'); // Add foreign key constraint
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
