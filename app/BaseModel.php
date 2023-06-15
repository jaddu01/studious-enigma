<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class BaseModel extends Model
{
    protected $hidden = [];
    public $locales;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->locales = config('translatable.locales');

        $prefix = Request::route() ? Request::route()->getPrefix() : '';

        if($prefix=='api/v1') {
            $this->hidden = ['deleted_at', 'created_at', 'updated_at','translations', 'status'];
            if(Request::route()->getName()=='order.store'){
                $this->hidden = ['deleted_at', 'created_at', 'updated_at', 'status','offer_data','data'];
            }

        }
    }
}
