<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierPurchaseProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_purchase_product_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('bar_code');
            $table->decimal('qty');
            $table->decimal('fee_qty')->nullable();
            $table->decimal('unit_cost');
            $table->decimal('selling_price');
            $table->decimal('purchase_price')->nullable();
            $table->decimal('best_price')->nullable();
            $table->decimal('mrp')->nullable();
            $table->decimal('landing_cost')->nullable();
            $table->decimal('tax');
            $table->decimal('taxable');
            $table->decimal('margin');
            $table->string('measurement_class')->nullable();
            $table->decimal('measurement_value')->nullable(); 
            $table->decimal('total');
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
        Schema::dropIfExists('supplier_purchase_product_details');
    }
}
