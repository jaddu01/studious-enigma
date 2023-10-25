<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosCustomerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_customer_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('users');
            $table->integer('order_id')->unsigned()->comment('order id from pos_customer_product_orders tbl');
            $table->foreign('order_id')->references('id')->on('pos_customer_product_orders');
            $table->decimal('payment')->default(0);
            $table->enum('payment_mode',['cash','online','wallet'])->default('cash');
            $table->string('transaction_no')->nullable();
            $table->enum('status',['paid','cancelled','pending'])->default('pending');            
            $table->string('description')->nullable();
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
        Schema::dropIfExists('pos_customer_payments');
    }
}
