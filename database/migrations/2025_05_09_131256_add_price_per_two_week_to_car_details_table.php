<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricePerTwoWeekToCarDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_details', function (Blueprint $table) {
            $table->string('price_per_two_week')->after('price_per_week')->nullable();
            $table->string('price_per_three_week')->after('price_per_two_week')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_details', function (Blueprint $table) {
            $table->dropColumn('price_per_two_week');
            $table->dropColumn('price_per_three_week');
        });
    }
}
