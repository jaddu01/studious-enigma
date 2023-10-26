<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PosCustomerOrderItem extends Model
{
    protected $table = 'pos_customer_order_items';
    protected $fillable = [
        'order_id', 'customer_id', 'vendor_product_id','product_id','price', 'qty', 'is_offer', 'offer_value', 'offer_type',
        'due_amount_of_customer', 'return_status', 'return_reason','is_offer','offer_data'
    ];
}
