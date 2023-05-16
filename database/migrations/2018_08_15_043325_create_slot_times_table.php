<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlotTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slot_times', function (Blueprint $table) {
            $table->increments('id');
            $table->string('to_time')->default(null);
            $table->string('from_time')->default(null);

            $table->integer('lock_time_hour')->default(100);
            $table->integer('lock_time_minutes')->default(100);
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
        Schema::dropIfExists('slot_times');
    }
}
