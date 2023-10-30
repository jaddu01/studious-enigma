<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierPurchaseAdditionalCharge extends Model
{
    protected $table = "supplier_purchase_additional_charges";
    protected $fillable =['supplier_id','charge_name','charge','supplier_bill_id'];
}
