<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuppliersPayment extends Model
{
    protected $table ='suppliers_payments';
    protected $fillable =['supplier_id','supplier_bill_purchase_id','payment_mode','payment_date','transaction_no','description','amount'];
}
