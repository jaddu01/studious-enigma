<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cms_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');
            $table->text('description');

            $table->unique(['cms_id','locale']);
            $table->foreign('cms_id')->references('id')->on('cms')->onDelete('cascade');
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
        Schema::dropIfExists('cms_translations');
    }
}
