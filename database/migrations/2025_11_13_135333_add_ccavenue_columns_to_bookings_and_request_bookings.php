<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCcavenueColumnsToBookingsAndRequestBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings_and_request_bookings', function (Blueprint $table) {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('order_id')->nullable()->unique();
            $table->string('tracking_id')->nullable();
            $table->string('payment_reference')->nullable();
            $table->json('payment_response')->nullable();
        });

        Schema::table('request_bookings', function (Blueprint $table) {
            $table->string('order_id')->nullable()->unique();
            $table->string('tracking_id')->nullable();
            $table->string('payment_reference')->nullable();
            $table->json('payment_response')->nullable();
        });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings_and_request_bookings', function (Blueprint $table) {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'tracking_id', 'payment_reference', 'payment_response']);
        });
        Schema::table('request_bookings', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'tracking_id', 'payment_reference', 'payment_response']);
        });
        });
    }
}
