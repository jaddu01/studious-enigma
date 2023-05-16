<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeekPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('week_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('saturday_slot_id');
            $table->integer('sunday_slot_id');
            $table->integer('monday_slot_id');
            $table->integer('tuesday_slot_id');
            $table->integer('wednesday_slot_id');
            $table->integer('thursday_slot_id');
            $table->integer('friday_slot_id');
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
        Schema::dropIfExists('week_packages');
    }
}
