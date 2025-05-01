<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricePerWeekToRequestBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_bookings', function (Blueprint $table) {
            $table->string('price_per_week')->after('price')->nullable();
            $table->string('price_per_day')->after('price_per_week')->nullable();
            $table->string('price_per_month')->after('price_per_day')->nullable();
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
            $table->dropColumn('price_per_week');
            $table->dropColumn('price_per_day');
            $table->dropColumn('price_per_month');
        });
    }
}
