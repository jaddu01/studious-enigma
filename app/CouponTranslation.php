<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
