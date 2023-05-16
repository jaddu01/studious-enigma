<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessLevelTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_level_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('access_level_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['access_level_id','locale']);
            $table->foreign('access_level_id')->references('id')->on('access_levels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_level_translations');
    }
}
