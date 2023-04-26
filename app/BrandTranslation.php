<?php

/**
 * @Author: abhi
 * @Date:   2021-08-30 20:07:28
 * @Last Modified by:   abhi
 * @Last Modified time: 2021-08-30 20:08:05
 */
namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class BrandTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','image','slug'];
    /**
     * @param $value
     * @return null|string
     */
    public function getImageAttribute($value)
    {
        return Helper::hasImage($value);
    }
}
