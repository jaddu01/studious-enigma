<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_times', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('delivery_time_id')->unsigned();
            $table->string('to_time')->default(null);
            $table->string('from_time')->default(null);
            $table->integer('total_order')->default(100);
            $table->enum('status',['0','1'])->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('delivery_times');
    }
}
