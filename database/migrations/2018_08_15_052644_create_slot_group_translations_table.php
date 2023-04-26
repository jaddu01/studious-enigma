<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlotGroupTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slot_group_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('slot_group_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['slot_group_id','locale']);
            $table->foreign('slot_group_id')->references('id')->on('slot_groups')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slot_group_translations');
    }
}
