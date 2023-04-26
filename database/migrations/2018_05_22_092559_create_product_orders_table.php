<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_code');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('vendor_product_id')->unsigned();
            $table->foreign('vendor_product_id')->references('id')->on('vendor_products');
            $table->enum('order_status',['N','O','S','D'])->default('N');
            $table->string('shipping_location');
            $table->string('delivery_time');
            $table->integer('payment_mode_id')->unsigned();
            $table->foreign('payment_mode_id')->references('id')->on('payment_modes');
            $table->decimal('delivery_charge');
            $table->decimal('tex');
            $table->decimal('total_amount');
            $table->string('transaction_id');
            $table->enum('transaction_status',['0','1'])->default('0');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**delivery_times
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_orders');
    }
}
