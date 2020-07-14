<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderHasProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_has_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('product_price_id')->unsigned();
            $table->string('name');
            $table->integer('qty');
            $table->float('price',8,2)->unsigned();
            $table->float('total',8,2)->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('product_price_id')->references('id')->on('product_price_details');
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
        Schema::dropIfExists('order_has_products');
    }
}
