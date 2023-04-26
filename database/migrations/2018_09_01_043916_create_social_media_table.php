<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('facebook_page');
            $table->string('twitter_page');
            $table->string('instagram_page');
            $table->string('linkedin_page');
            $table->string('whatsapp_share');
            $table->string('facebook_share');
            $table->string('instagram_share');
            $table->string('twitter_share');
            $table->string('linkedin_share');
            $table->string('other_share');
            $table->string('facebook_follow');
            $table->string('twitter_follow');
            $table->string('instagram_follow');
            $table->string('linkedin_follow');
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
        Schema::dropIfExists('social_media');
    }
}
