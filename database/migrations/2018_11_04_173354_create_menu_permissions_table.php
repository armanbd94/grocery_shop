<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->foreign('menu_id')->references('id')->on('menus');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->integer('created_by')->unsigned();
            $table->integer('modified_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('modified_by')->references('id')->on('users'); 
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
        Schema::dropIfExists('menu_permissions');
    }
}
