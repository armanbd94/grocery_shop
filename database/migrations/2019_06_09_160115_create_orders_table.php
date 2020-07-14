<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_no');
            $table->integer('customer_id')->unsigned();
            $table->integer('billing_address_id')->unsigned();
            $table->integer('shipping_address_id')->unsigned();
            $table->float('total_amount',8,2);
            $table->string('coupon_code')->nullable();
            $table->string('coupon_discount_amount')->nullable();
            $table->integer('order_status');
            $table->integer('delivery_status');
            $table->date('delivery_date');
            $table->string('delivery_time');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('billing_address_id')->references('id')->on('customer_addresses');
            $table->foreign('shipping_address_id')->references('id')->on('customer_addresses');
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
        Schema::dropIfExists('orders');
    }
}
