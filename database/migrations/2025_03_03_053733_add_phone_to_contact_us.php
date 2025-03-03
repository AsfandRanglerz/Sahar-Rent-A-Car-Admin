<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneToContactUs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_us', function (Blueprint $table) {
            $table->string('phone')->after('id')->nullable();
            $table->string('address')->after('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_us', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('address');
        });
    }
}
