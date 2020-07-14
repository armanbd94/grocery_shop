<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coupon_name')->unique();
            $table->string('coupon_code')->unique();
            $table->integer('discount');
            $table->float('total',8,2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('uses_total')->nullable();
            $table->integer('uses_customer')->nullable();
            $table->enum('status', ['1', '2'])->default('1')->comment = "1 = enabled,2 = disabled";
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
        Schema::dropIfExists('coupons');
    }
}
