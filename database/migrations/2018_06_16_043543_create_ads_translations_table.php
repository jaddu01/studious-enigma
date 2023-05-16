<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ads_id')->unsigned();
            $table->string('locale')->index();

            $table->string('title');
            $table->string('image');

            $table->unique(['ads_id','locale']);
            $table->foreign('ads_id')->references('id')->on('ads')->onDelete('cascade');
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
        Schema::dropIfExists('ads_translations');
    }
}
