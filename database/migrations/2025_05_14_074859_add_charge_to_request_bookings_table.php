<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChargeToRequestBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_bookings', function (Blueprint $table) {
             $table->string('charge')->nullable()->after('price');
            $table->string('city')->nullable()->after('charge');
            $table->string('total_days')->nullable()->after('city');
            $table->string('vat')->nullable()->after('total_days');
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
            $table->dropColumn('charge');
            $table->dropColumn('city');
            $table->dropColumn('total_days');
            $table->dropColumn('vat');
        });
    }
}
