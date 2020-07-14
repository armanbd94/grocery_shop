<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_name')->unique();
            $table->string('category_slug')->unique();
            $table->integer('parent_id')->default('0');
            $table->enum('status',['1','2'])->default('1')->comment = "1 = active, 2 = inactive";
            $table->integer('creator_id')->unsigned();
            $table->integer('modifier_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('modifier_id')->references('id')->on('users');
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
        Schema::dropIfExists('categories');
    }
}
