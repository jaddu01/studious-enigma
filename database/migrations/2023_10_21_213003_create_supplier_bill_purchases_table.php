<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierBillPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_bill_purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->date('bill_date');
            $table->date('due_date');
            $table->date('shipping_date');
            $table->decimal('bill_amount');
            $table->decimal('tax_amount')->nullable();
            $table->string('invoice_no');
            $table->string('reference_bill_no')->nullable();
            $table->string('payment_term')->nullable();
            $table->enum('tax_type',['Default','Tax Inclusive','Tax Exclusive','Out Of Score'])->default('Default');
            $table->enum('status',['due','paid']);
            $table->string('description')->nullable();
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
        Schema::dropIfExists('supplier_bill_purchases');
    }
}
