<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('access_level_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->string('dob');
            $table->enum('gender',['male', 'female']);
            $table->string('phone_number');
            $table->string('image')->default(null);
            $table->string('lat')->default(null);
            $table->string('long')->default(null);
            $table->string('device_id')->default(null);
            $table->enum('device_type',['A', 'I'])->default(null);
            $table->enum('role',['admin','admin_user','user'])->default('user');
            $table->enum('	status',['0','1'])->default(0);
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
        Schema::dropIfExists('users');
    }
}
