<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('facebook_app_id');
            $table->string('facebook_app_secret_key');
            $table->string('twitter_app_id');
            $table->string('twitter_app_secret_key');
            $table->string('ios_customer_app_google_key');
            $table->string('android_customer_app_google_key');
            $table->string('android_shopper_app_google_key');
            $table->string('android_driver_app_google_key');
            $table->string('admin_panel_google_key');
            $table->string('google_analytics_code');
            $table->string('facebook_analytics_code');
            $table->string('customer_app_id');
            $table->string('customer_app_rest_key');
            $table->string('shopper_app_id');
            $table->string('shopper_app_rest_key');
            $table->string('driver_app_id');
            $table->string('driver_app_rest_key');
            $table->string('admin_panel_id');
            $table->string('admin_panel_rest_key');
            $table->string('all_order_redirect_url');
            $table->string('admin_notification_redirect_url');
            $table->string('shopper_notification_redirect_url');
            $table->string('driver_notification_redirect_url');

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
        Schema::dropIfExists('api_settings');
    }
}
