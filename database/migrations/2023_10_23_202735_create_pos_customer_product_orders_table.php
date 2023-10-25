<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosCustomerProductOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_customer_product_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('users');
            $table->integer('pos_user_id')->unsigned();
            $table->foreign('pos_user_id')->references('id')->on('users');
            $table->string('invoice_no');
            $table->decimal('extra_discount')->default(0);
            $table->decimal('delivery_charge')->default(0);
            $table->decimal('due_amount')->default(0);
            $table->enum('mode',['offline','online'])->default('offline');
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
        Schema::dropIfExists('pos_customer_product_orders');
    }
}
