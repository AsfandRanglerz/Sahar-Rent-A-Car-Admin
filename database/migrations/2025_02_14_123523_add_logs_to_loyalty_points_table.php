<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogsToLoyaltyPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loyalty_points', function (Blueprint $table) {
            $table->unsignedBigInteger('added_by_subadmin')->nullable()->after('id'); // Store who added the user
            $table->foreign('added_by_subadmin')->references('id')->on('subadmins')->onDelete('cascade')->onUpdate('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loyalty_points', function (Blueprint $table) {
            $table->dropForeign(['added_by_subadmin']);
            $table->dropColumn('added_by_subadmin');
        });
    }
}
