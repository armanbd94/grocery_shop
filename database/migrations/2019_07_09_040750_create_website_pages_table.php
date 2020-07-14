<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsitePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('page_name')->unique();
            $table->string('page_url')->unique();
            $table->text('page_content');
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
        Schema::dropIfExists('website_pages');
    }
}
