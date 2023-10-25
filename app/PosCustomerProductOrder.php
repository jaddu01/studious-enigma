<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PosCustomerProductOrder extends Model
{
    protected $table = 'pos_customer_product_orders';
    protected $fillable =['customer_id','pos_user_id','invoice_no','extra_discount','delivery_charge','due_amount','mode',
    'description'];
}
