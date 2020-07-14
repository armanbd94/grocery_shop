<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('website_title')->nullable();
            $table->string('website_footer_text')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('contact_address')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('footer_logo')->nullable();
            $table->string('favicon_icon')->nullable();
            $table->longText('social_media')->nullable();
            $table->longText('mail_info')->nullable();
            $table->string('invoice_prefix')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
