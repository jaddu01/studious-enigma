<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_accesses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('access_level_id')->unsigned();
            $table->foreign('access_level_id')->references('id')->on('access_levels');
            $table->integer('permission_modal_id')->unsigned();
            $table->foreign('permission_modal_id')->references('id')->on('permission_modals');
            $table->string('type')->default(null);
            $table->enum('status',['0','1'])->default(1);
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
        Schema::dropIfExists('permission_accesses');
    }
}
