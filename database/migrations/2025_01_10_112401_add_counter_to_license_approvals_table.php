<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCounterToLicenseApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('license_approvals', function (Blueprint $table) {
            $table->string('counter')->default(0)->after('action');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('license_approvals', function (Blueprint $table) {
            $table->dropColumn('counter');
        });
    }
}
