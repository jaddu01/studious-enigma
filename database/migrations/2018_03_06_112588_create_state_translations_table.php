<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('state_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['state_id','locale']);
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('state_translations');
    }
}
