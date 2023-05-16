<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeekPackageTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('week_package_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('week_package_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['week_package_id','locale']);
            $table->foreign('week_package_id')->references('id')->on('week_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('week_package_translations');
    }
}
