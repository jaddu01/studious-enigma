<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryDayTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_day_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('delivery_day_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['delivery_day_id','locale']);
            $table->foreign('delivery_day_id')->references('id')->on('delivery_days')->onDelete('cascade');
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
        Schema::dropIfExists('delivery_day_translations');
    }
}
