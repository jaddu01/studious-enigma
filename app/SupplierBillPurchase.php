<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Database\Eloquent\Model;

class SupplierBillPurchase extends Model
{
    use SoftDeletes;

    protected $table = 'supplier_bill_purchases';
    protected $fillable = [
        'supplier_id', 'bill_date', 'due_date', 'shipping_date', 'bill_amount',
        'tax_amount', 'invoice_no', 'reference_invoice_no', 'payment_term', 'tax_type', 'description','net_amount','total_amount','total_additional_charge',
        'due_amount','paid_amount'
    ];
}
