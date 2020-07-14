<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('brand_id')->nullable();
            $table->string('product_name');
            $table->string('product_slug')->unique()->nullable();
            $table->string('product_variation')->nullable();
            $table->string('featured_image');
            $table->text('description')->nullable();
            $table->integer('discount')->default('0');
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
        Schema::dropIfExists('products');
    }
}
