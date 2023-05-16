<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZoneTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zone_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('zone_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');
            $table->string('description');

            $table->unique(['zone_id','locale']);
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zone_translations');
    }
}
