<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosCustomerOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_customer_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->comment('order id from pos_customer_product_orders tbl');
            $table->foreign('order_id')->references('id')->on('pos_customer_product_orders');
            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('users');
            $table->integer('vendor_product_id')->unsigned();
            $table->foreign('vendor_product_id')->references('id')->on('vendor_products');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->decimal('price')->default(0);
            $table->integer('qty')->default(0);
            $table->enum('is_offer',[0,1])->default(0);
            $table->decimal('offer_value')->default(0);
            $table->string('offer_type')->nullable();
            $table->decimal('due_amount_of_customer')->decimal(0);
            $table->enum('return_status',[0,1])->default(0);
            $table->string('return_reason')->nullable();
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
        Schema::dropIfExists('pos_customer_order_items');
    }
}
