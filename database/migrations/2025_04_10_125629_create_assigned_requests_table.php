<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignedRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigned_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_booking_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('dropoff_driver_id')->nullable();
            // $table->unsignedBigInteger('booking_id')->nullable(); // if needed
            $table->string('status')->default(3)->nullable();
            $table->timestamps();

            $table->foreign('request_booking_id')->references('id')->on('request_bookings')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->foreign('dropoff_driver_id')->references('id')->on('drivers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assigned_requests');
    }
}
