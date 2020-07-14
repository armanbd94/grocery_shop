<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();;
            $table->string('name');
            $table->string('email', 100)->unique();
            $table->string('mobile_no', 15);
            $table->string('additional_mobile_no', 15)->nullable();
            $table->tinyInteger('gender')->comment = "1= male, 2 = female";
            $table->string('photo')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 100);
            $table->text('address')->nullable();
            $table->tinyInteger('is_active')->default('1')->comment = "0 = inactive,1 = active";
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
