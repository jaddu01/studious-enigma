<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeasurementClassTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurement_class_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('measurement_class_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['measurement_class_id','locale'],'measurement_class_translations_measurement_id');
            $table->foreign('measurement_class_id')->references('id')->on('measurement_classes')->onDelete('cascade');
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
        Schema::dropIfExists('measurement_class_translations');
    }
}
