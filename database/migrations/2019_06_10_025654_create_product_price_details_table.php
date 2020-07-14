<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPriceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_price_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->string('weight');
            $table->string('unitname');
            $table->string('price');
            $table->enum('is_default',['1','2'])->default('2')->comment = "1 = yes, 2 = no";
            $table->foreign('product_id')->references('id')->on('products');
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
        Schema::dropIfExists('product_price_details');
    }
}
