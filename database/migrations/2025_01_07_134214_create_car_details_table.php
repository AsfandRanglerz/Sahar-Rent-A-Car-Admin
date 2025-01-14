<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_details', function (Blueprint $table) {
            $table->id();
            $table->string('car_name')->nullable();
            $table->string('availability')->nullable();
            $table->string('pricing')->nullable();
            $table->string('image')->nullable();
            $table->string('durations')->nullable();
            $table->string('call_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('passengers')->nullable();
            $table->string('luggage')->nullable();
            $table->string('doors')->nullable();
            $table->string('car_type')->nullable();
            $table->string('car_play')->nullable();
            $table->string('sanitized')->nullable();
            $table->string('car_feature')->nullable();
            $table->string('delivery')->nullable();
            $table->string('pickup')->nullable();
            $table->string('travel_distance')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_details');
    }
}
