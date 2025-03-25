<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverForgotOTPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_forgot_o_t_p_s', function (Blueprint $table) {
            $table->id();
            $table->string('identifier'); // Email or phone
            $table->string('otp');
            $table->string('verified')->nullable();
            $table->string('otp_token')->unique();
            $table->timestamp('expires_at');
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
        Schema::dropIfExists('driver_forgot_o_t_p_s');
    }
}
