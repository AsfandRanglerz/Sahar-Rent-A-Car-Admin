<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPickupAndDropoffDriverToRequestBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('dropoff_driver_id')->nullable()->after('driver_id');
           
            $table->foreign('dropoff_driver_id')->references('id')->on('drivers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_bookings', function (Blueprint $table) {
            $table->dropForeign(['dropoff_driver_id']);
            $table->dropColumn('dropoff_driver_id');
        });
    }
}
