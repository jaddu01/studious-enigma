<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuppliersDueAmount extends Model
{
    protected $table = "suppliers_due_amounts";
    protected $fillable =['id','supplier_id','due_amount'];

}
