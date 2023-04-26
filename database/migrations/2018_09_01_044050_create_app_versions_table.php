<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('android_current_version');
            $table->string('android_mandatory_update');
            $table->string('android_main_tenance_mode');
            $table->string('ios_current_version');
            $table->string('ios_mandatory_update');
            $table->string('ios_main_tenance_mode');
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
        Schema::dropIfExists('app_versions');
    }
}
