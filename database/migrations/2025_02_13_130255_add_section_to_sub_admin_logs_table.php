<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSectionToSubAdminLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_admin_logs', function (Blueprint $table) {
            $table->string('section')->nullable()->after('subadmin_id');
            $table->string('action')->nullable()->after('section');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_admin_logs', function (Blueprint $table) {
            $table->dropColumn(['section', 'action']);
        });
    }
}
