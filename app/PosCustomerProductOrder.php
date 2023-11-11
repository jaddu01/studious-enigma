<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PosCustomerProductOrder extends Model
{
    protected $table = 'pos_customer_product_orders';
    protected $fillable =['customer_id','pos_user_id','invoice_no','extra_discount','delivery_charge','due_amount','mode',
    'description','order_date','order_time','bill_amount','changes','payment','online_order_id','pos_state'];

   
    public function PosCustomerPayment()
    {
        return $this->hasOne(PosCustomerPayment::class, 'order_id');
    }

    public function PosCustomerOrderItem(){
        return $this->hasMany(PosCustomerOrderItem::class,'order_id','id');
    }
}
