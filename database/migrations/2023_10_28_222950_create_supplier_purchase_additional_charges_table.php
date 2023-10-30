<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierPurchaseAdditionalChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_purchase_additional_charges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_bill_id')->unsigned();
            $table->foreign('supplier_bill_id')->references('id')->on('supplier_bill_purchases');
            $table->integer('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->string('charge_name')->nullable();
            $table->decimal('charge')->default(0);
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
        Schema::dropIfExists('supplier_purchase_additional_charges');
    }
}
