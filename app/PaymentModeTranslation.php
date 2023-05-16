<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentModeTranslation extends BaseModel
{
    public $timestamps = false;
    protected $fillable = ['name','details'];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
