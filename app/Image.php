<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class Image extends BaseModel
{
    protected $fillable = [
       'name','image_id','image_type'
    ];
    public function imageable()
    {
        return $this->morphTo();
    }

    public function getNameAttribute($value)
    {
        return Helper::imageNotFound($value);
    }
}
