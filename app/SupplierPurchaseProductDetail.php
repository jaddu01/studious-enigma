<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierPurchaseProductDetail extends Model
{
    protected $table = "supplier_purchase_product_details";
    protected $fillable = [
        'supplier_id','supplier_bill_id', 'product_id', 'bar_code', 'qty', 'free_qty', 'unit_cost', 'selling_price',
        'best_price', 'mrp',  'net_rate','gst_amount', 'margin', 'measurement_class', 'measurement_value',
        'total'
    ];
}
