<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierPurchaseProductDetail extends Model
{
    protected $table = "supplier_purchase_product_details";
    protected $fillable = [
        'supplier_id', 'product_id', 'bar_code', 'qty', 'fee_qty', 'unit_cost', 'selling_price',
        'purchase_price', 'best_price', 'mrp', 'landing_cost', 'tax', 'taxable', 'margin', 'measurement_class', 'measurement_value',
        'total'
    ];
}
