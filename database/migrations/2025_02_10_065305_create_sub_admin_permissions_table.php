<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubAdminPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subadmin_id');
            $table->string('menu');
            $table->string('add')->default(0);
            $table->string('edit')->default(0);
            $table->string('delete')->default(0);
            $table->string('view')->default(0);
            $table->timestamps();

            $table->foreign('subadmin_id')->references('id')->on('subadmins')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_admin_permissions');
    }
}
