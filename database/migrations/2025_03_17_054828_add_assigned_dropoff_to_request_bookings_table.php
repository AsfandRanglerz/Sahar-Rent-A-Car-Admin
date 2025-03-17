<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignedDropoffToRequestBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_bookings', function (Blueprint $table) {
            $table->string('assigned_dropoff')->default(0)->after('driver_required');
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
            $table->dropColumn('assigned_dropoff');
        });
    }
}
