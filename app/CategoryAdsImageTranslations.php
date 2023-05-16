<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class CategoryAdsImageTranslations extends Model
{
    public $timestamps = false;
    protected $table = 'category_ads_image_translations';
    protected $fillable = ['title','image'];

    public function getImageAttribute($value)
    {
        return Helper::hasImage($value);
    }
  /*  protected $touches = ['ads'];
    public function ads()
    {
        return $this->belongsTo(Ads::class);
    }*/
}
