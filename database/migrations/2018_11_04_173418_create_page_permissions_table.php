<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->foreign('page_id')->references('id')->on('pages');
            $table->foreign('role_id')->references('id')->on('roles');
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
        Schema::dropIfExists('page_permissions');
    }
}
