<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PosCustomerPayment extends Model
{
    protected $table ='pos_customer_payments';
    protected $fillable =['customer_id','order_id','payment_mode','transaction_no','payment','status','description'];
}
