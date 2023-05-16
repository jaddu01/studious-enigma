<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('active_payment_page',['yes','no'])->default('no');
            $table->enum('cash_on_delivery',['yes','no'])->default('no');
            $table->enum('wallet',['yes','no'])->default('no');
            $table->enum('credit_card',['yes','no'])->default('no');
            $table->enum('paypal',['yes','no'])->default('no');
            $table->string('stripe_secret_key');
            $table->string('stripe_public_key');
            $table->string('paypal_account_email');
            $table->string('paypal_currency');
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
        Schema::dropIfExists('payments');
    }
}
