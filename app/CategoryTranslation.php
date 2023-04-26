<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','image','banner_image','slug'];
    /**
     * @param $value
     * @return null|string
     */
    public function getImageAttribute($value)
    {
        return Helper::hasImage($value);
    }
    public function category()
    {
       return $this->belongsTo(Category::class);
    }
}
